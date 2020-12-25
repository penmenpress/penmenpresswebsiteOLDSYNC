/**
 * WordPress Dependencies
 */
const { __ } = wp.i18n;

const {
   Fragment,
   Component
} = wp.element;

const { compose, withState } = wp.compose;

const {
   PanelBody,
   PanelRow,
   Dropdown,
   CheckboxControl,
   TextareaControl,
   Button,
   Spinner
} = wp.components;

const { withSelect, withDispatch } = wp.data;
const { PostScheduleLabel, PostSchedule } = wp.editor;

/**
 * Internal dependencies
 */


import {
   getActionHistoryIdFromURL,
   getTaskUserFromURL
} from "../util";

export class SignoffLastStep extends Component {

   constructor() {
      super(...arguments);

      this.state = {
         signoffButtonText: __('Sign Off', 'oasisworkflow'),
         comments: '',
         actionHistoryId: getActionHistoryIdFromURL(),
         taskUser: getTaskUserFromURL(),
         isImmediatelyChecked: false,
         originalPublishDate: this.props.publishDate,
         redirectingLoader: 'hide',
         submitSpinner: "hide",
         submitButtonDisable: false
      }

   }

   componentDidMount() {
      let customWorkflowTerminology = this.props.owSettings.terminology_settings.oasiswf_custom_workflow_terminology;

      if (customWorkflowTerminology) {
         let signoffButtonText = customWorkflowTerminology.signOffText;
         this.setState({
            signoffButtonText
         });
      }
   }

   /**
    * handle successful completion of workflow
    */
   handleWorkflowComplete(event) {
      this.props.onSave();

      this.setState({
         submitSpinner: "show",
         submitButtonDisable: true
      });

      let form_data = {
         post_id: this.props.postId,
         history_id: this.state.actionHistoryId,
         immediately: this.state.isImmediatelyChecked,
         task_user: this.state.taskUser,
         publish_datetime: this.props.publishDate
      };

      // TODO: introducing a delay to allow the post to be saved and then invoke the sign off
      var that = this;
      setTimeout(function () {
         that.invokeSignoffAPI(form_data);
      }, 500);
   }

   invokeSignoffAPI(form_data) {
      wp.apiFetch({ path: '/oasis-workflow/v1/workflows/signoff/workflowComplete/', method: 'POST', data: form_data }).then(
         (submitResponse) => {
            console.log("submit response: " + submitResponse);
            // Redirect user to inbox page
            if (submitResponse.redirect_link !== "") {
               this.setState({
                  redirectingLoader: 'show'
               })
               window.location.href = submitResponse.redirect_link;
            } else {
               this.props.handleResponse(submitResponse);
               this.props.pageRefresh();
            }
         },
         (err) => {
            console.log(err);
            return err;
         }
      );
   }

   /**
    * handle cancellation of workflow on the last step
    */
   handleWorkflowCancel(event) {
      this.setState({
         submitSpinner: "show",
         submitButtonDisable: true
      });

      let form_data = {
         post_id: this.props.postId,
         history_id: this.state.actionHistoryId,
         comments: this.state.comments,
         task_user: this.state.taskUser
      };

      wp.apiFetch({ path: '/oasis-workflow/v1/workflows/signoff/workflowCancel/', method: 'POST', data: form_data }).then(
         (submitResponse) => {
            this.props.handleResponse(submitResponse);
         },
         (err) => {
            console.log(err);
            return err;
         }
      );
   }

   /**
    * handle immediately checkbox change
    * @param {boolean} checked 
    */
   onImmediatelyChange(checked) {
      let currentDate = new Date();
      let newDate = '';
      if (checked) {
         this.setState({
            isImmediatelyChecked: true
         });
         newDate = currentDate; //publish date set to now
      } else {
         this.setState({
            isImmediatelyChecked: false
         });
         newDate = this.state.originalPublishDate; // publish date set to the original date
      }

      this.props.editPost({ date: newDate });
      this.props.onSave();
   }

   render() {
      const { isSaving, postMeta } = this.props;
      const { isImmediatelyChecked, signoffButtonText, redirectingLoader, submitSpinner, submitButtonDisable } = this.state;

      if (redirectingLoader === 'show') {
         return (
            <div>
               <PanelBody>
                  {__('redirecting...', 'oasisworkflow')}
               </PanelBody>
            </div>
         )
      }

      return (
         <div>
            {this.props.stepDecision === 'success' ?
               (
                  <div>
                     <PanelRow>
                        <div id="owf-success-message" className="notice notice-warning is-dismissible">
                           <p>{__('This is the last step in the workflow. Are you sure to complete the workflow?', 'oasisworkflow')}</p>
                        </div>
                     </PanelRow>
                     <PanelRow className="edit-post-post-schedule">
                        <label>{__('Publish', 'oasisworkflow') + ':'} </label>
                        <Dropdown
                           position="bottom left"
                           contentClassName="edit-post-post-schedule__dialog"
                           renderToggle={({ onToggle, isOpen }) => (
                              <Fragment>
                                 <Button
                                    type="button"
                                    onClick={onToggle}
                                    aria-expanded={isOpen}
                                    aria-live="polite"
                                    isLink
                                 >
                                    <PostScheduleLabel />
                                 </Button>
                              </Fragment>
                           )}
                           renderContent={() => (<PostSchedule />)}
                        />
                     </PanelRow>
                     <PanelRow>
                        <CheckboxControl
                           label={__("Publish Immediately?", 'oasisworkflow')}
                           checked={isImmediatelyChecked}
                           onChange={this.onImmediatelyChange.bind(this)}
                        />
                     </PanelRow>
                     <PanelRow>
                        <Button
                           isPrimary
                           isBusy={isSaving}
                           focus="true"
                           disabled={submitButtonDisable}
                           onClick={this.handleWorkflowComplete.bind(this)}
                        >
                           {signoffButtonText}
                        </Button>
                        <div className="owf-spinner">
                           {submitSpinner == "show" ?
                              (
                                 <Spinner />
                              ) : ""
                           }
                        </div>
                     </PanelRow>
                  </div>
               )
               :
               (
                  <div>
                     <PanelRow>
                        <div id="owf-success-message" className="notice notice-error is-dismissible">
                           <p>{__('There are no further steps defined in the workflow. Do you want to cancel the post/page from the workflow?', 'oasisworkflow')}</p>
                        </div>
                     </PanelRow>
                     <PanelRow>
                        <TextareaControl
                           label={__('Comments', 'oasisworkflow') + ':'}
                           value={this.state.comments}
                           onChange={(comments) => this.setState({ comments })}
                        />
                     </PanelRow>
                     <PanelRow>
                        <Button
                           isPrimary
                           isBusy={isSaving}
                           focus="true"
                           disabled={submitButtonDisable}
                           onClick={this.handleWorkflowCancel.bind(this)}
                        >
                           {signoffButtonText}
                        </Button>
                        <div className="owf-spinner">
                           {submitSpinner == "show" ?
                              (
                                 <Spinner />
                              ) : ""
                           }
                        </div>
                     </PanelRow>
                  </div>
               )
            }
         </div>
      )
   }
}

export default compose([
   withSelect((select) => {
      const { getCurrentPostId, getEditedPostAttribute } = select('core/editor');
      const { getOWSettings } = select('plugin/oasis-workflow');
      return {
         postId: getCurrentPostId(),
         publishDate: getEditedPostAttribute('date'),
         postMeta: getEditedPostAttribute('meta'),
         owSettings: getOWSettings()
      };
   }),
   withDispatch((dispatch) => ({
      onSave: dispatch('core/editor').savePost,
      editPost: dispatch('core/editor').editPost,
      autosave: dispatch('core/editor').autosave,
      pageRefresh: dispatch('core/editor').refreshPost
   }))
])(SignoffLastStep);