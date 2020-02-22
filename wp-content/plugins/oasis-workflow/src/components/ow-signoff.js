/**
 * WordPress Dependencies
 */
const { __ } = wp.i18n;

const { pick, trim, isEmpty } = lodash;

const {
   Fragment,
   Component,
   createRef
} = wp.element;

const { compose, withState } = wp.compose;

const {
   PanelBody,
   PanelRow,
   SelectControl,
   Dropdown,
   TextareaControl,
   CheckboxControl,
   Button,
   Spinner
} = wp.components;

const { withSelect, withDispatch } = wp.data;

/**
 * Internal dependencies
 */
import TaskPriorities from "./ow-task-priority-select-control";
import OWDueDatePicker from "./ow-due-date-picker";
import OWDueDateLabel from "./ow-due-date-label";
import SignoffLastStep from "./ow-signoff-last-step";
import {
   getActionHistoryIdFromURL,
   getTaskUserFromURL,
   getSignOffActions,
   getStepAssignees
} from "../util";

export class Signoff extends Component {
   constructor() {
      super(...arguments);

      this.signoffPanelRef = createRef();

      this.state = {
         signoffButtonText: __( 'Sign Off', 'oasisworkflow' ),
         assignActorLabel : __( 'Assign Actor(s)', 'oasisworkflow' ),
         dueDateLabel: __( 'Due Date', 'oasisworkflow' ),
         displayDueDate : '',
         actions: [],
         selectedAction: '',
         signoffSteps: [{ label: '', value: '' }],
         selectedStep: '',
         selectedPriority: '2normal',
         assignee: [],
         selectedAssignees: [],
         assignToAll: false,
         actionHistoryId: getActionHistoryIdFromURL(),
         taskUser: getTaskUserFromURL(),
         comments: '',
         isLastStep: false,
         lastStepDecision: 'success',
         validationErrors: [],
         redirectingLoader: 'hide',
         stepSpinner: 'hide',
         assigneeSpinner: "hide",
         submitSpinner: "hide",         
         submitButtonDisable: false
      }
   }

   componentDidMount() {
      let customWorkflowTerminology = this.props.owSettings.terminology_settings.oasiswf_custom_workflow_terminology;
      let workflowSettings = this.props.owSettings.workflow_settings;

      if( customWorkflowTerminology ) {
         let signoffButtonText = customWorkflowTerminology.signOffText;
         let assignActorLabel = customWorkflowTerminology.assignActorsText;
         let dueDateLabel = customWorkflowTerminology.dueDateText;
         this.setState({
            signoffButtonText,
            assignActorLabel,
            dueDateLabel,
         });
      }

      if( workflowSettings ) {
         let displayDueDate = workflowSettings.oasiswf_default_due_days;
         this.setState({
            displayDueDate
         });
      }

      // fetch step action details - essentially, show review actions or assignment/publish actions
      wp.apiFetch({ path: '/oasis-workflow/v1/workflows/signoff/stepActions/actionHistoryId=' + this.state.actionHistoryId, method: 'GET' }).then(
         (step_decision) => {
            let process = step_decision.process;
            this.setState({
               actions: getSignOffActions(process)
            });
         },
         (err) => {
            console.log(err);			
            return err;
         }
      );
   }

   getSignoffSteps(stepDecision) {
      let postId = this.props.postId;
      let decision = "success";

      // Set selected stepDecision
      this.setState({
         selectedAction: stepDecision,
         stepSpinner: "show"
      });

      if ("complete" === stepDecision) {
         decision = "success";
      }
      if ("unable" === stepDecision) {
         decision = "failure";
      }
      // get next steps depending on the step/task decision
      wp.apiFetch({ path: '/oasis-workflow/v1/workflows/signoff/nextSteps/actionHistoryId=' + this.state.actionHistoryId + '/decision=' + decision + '/postId=' + postId, method: 'GET' }).then(
         (stepdata) => {
            if (stepdata.steps === "") { // this is the last step, and so, we didn't get any next steps               
               this.setState({
                  isLastStep: true,
                  lastStepDecision: decision,
                  stepSpinner: "hide"
               });
            } else {
               this.setState({
                  isLastStep: false,
                  lastStepDecision: 'success'
               });               
               let steps = stepdata.steps.map((step) => pick(step, ['step_id', 'step_name']));
               let signoffSteps = [];

               // if there is more than one possible next step
               if (steps.length !== 1) {
                  signoffSteps.push({ label: __( "Select Step", 'oasisworkflow' ), value: "" })
               }

               steps.map(step => { signoffSteps.push({ label: step.step_name, value: step.step_id }) })

               this.setState({
                  signoffSteps: signoffSteps,
                  stepSpinner: "hide"
               });

               // if there is only one possible next step, auto select it
               if (steps.length == 1) {
                  this.getSelectedStepDetails(signoffSteps[0]['value']);
                  this.setState({
                     selectedStep: signoffSteps[0]['value']
                  });
               }
            }
            return stepdata;

         },
         (err) => {
            console.log(err);			
            return err;
         }
      );
   }

   /**
    * For the selected step, get other details, like assignee list, assignToAll flag etc
    * 
    * @param {Integer} stepId 
    */
   getSelectedStepDetails(stepId) {
      let postId = this.props.postId;
      this.setState({
         selectedStep: stepId,
         assigneeSpinner: "show"
      });
      wp.apiFetch({ path: '/oasis-workflow/v1/workflows/signoff/stepDetails/actionHistoryId=' + this.state.actionHistoryId + '/stepId=' + stepId + '/postId=' + postId, method: 'GET' }).then(
         (stepdata) => {
            let errors = [];
            let availableAssignees = [];
            let assignToAll = stepdata.assign_to_all === 1 ? true : false;
            let assignees = stepdata.users;

            this.props.setDueDate({ "dueDate": stepdata.due_date });

            // Display Validation Message if no user found for the step
            if (assignees.length === 0) {
               errors.push(__( "No users found to assign the task.", 'oasisworkflow' ) );
               this.setState({
                  validationErrors: errors,
                  assignee: []
               });

               // scroll to the top, so that the user can see the error
               this.signoffPanelRef.current.scrollIntoView();
               return;
            }

            // Set and Get Assignees from the util function
            let stepAssignees = getStepAssignees({ "assignees" : assignees, "assignToAll" : assignToAll });
            availableAssignees = stepAssignees.availableAssignees;

            this.setState({
               assignee: availableAssignees,
               assignToAll: assignToAll,
               selectedAssignees : stepAssignees.selectedAssignees,
               assigneeSpinner: "hide"
            });
            return stepdata;
         },
         (err) => {
            console.log(err);			
            return err;
         }
      );
   }

   /**
   * handle priority change
   * @param {*} selectedPriority 
   */
   handleOnPriorityChange(selectedPriority) {
      this.setState({
         selectedPriority
      });
   }

   /**
    * validate sign off
    * @param {Object} data 
    */
   validateSignoff(data) {
      const errors = [];
      let current_date = new Date();
      current_date = moment(current_date).format('YYYY-MM-DD');
      let due_date = moment(data.due_date).format('YYYY-MM-DD');

      if (data.step_id === '') {
         errors.push(__('Please select a step.' , 'oasisworkflow' ));
      }

      if (data.due_date === '') {
         errors.push(__('Please enter a due date.', 'oasisworkflow'));
      }

      if (data.due_date !== '' && moment(current_date).isAfter(due_date) == true) {
         errors.push(__('Due date must be greater than the current date.', 'oasisworkflow'));
      }

      if (data.assignees.length === 0 && !this.state.assignToAll) {
         errors.push(__('No assigned actor(s).', 'oasisworkflow'));
      }

      return errors;
   }   

   /**
   * handle form submit for sign off
   */
   handleSignoff( event ) {

      this.setState({ 
         submitSpinner : "show",
         submitButtonDisable : true
      });

      let form_data = {
         post_id: this.props.postId,
         step_id: this.state.selectedStep,
         decision: this.state.selectedAction,
         priority: this.state.selectedPriority,
         assignees: this.state.selectedAssignees,
         due_date: this.props.dueDate,
         comments: this.state.comments,
         history_id: this.state.actionHistoryId,
         hideSignOff: false,
         task_user: this.state.taskUser
      };

      // save the post
      this.props.onSave();

      const errors = this.validateSignoff(form_data);

      if (errors.length > 0) {
         this.setState({
            validationErrors: errors,
            submitSpinner : "hide",
            submitButtonDisable : false
         });

         // scroll to the top, so that the user can see the error
         this.signoffPanelRef.current.scrollIntoView();

         return;
      }

      this.setState({
         validationErrors: []
      });
      
      // TODO: introducing a delay to allow the post to be saved and then invoke the sign off
      var that = this;
      setTimeout( function() {
         that.invokeSignoffAPI( form_data );
      }, 500 );      
   }
   
   invokeSignoffAPI(form_data) {
      wp.apiFetch({ path: '/oasis-workflow/v1/workflows/signoff/', method: 'POST', data: form_data }).then(
         (submitResponse) => {
            if (submitResponse.new_action_history_id != this.state.actionHistoryId) {
               this.setState({
                  hideSignOff : true,
                  redirectingLoader : 'show'
               })
            }
            // Redirect user to inbox page
            if( submitResponse.redirect_link !== "" ) {
               window.location.href = submitResponse.redirect_link;
            } else {
               this.props.handleResponse(submitResponse);
            }
         },
         (err) => {
            console.log(err);
            return err;
         }
      );
   }

   render() {
      const { isSaving, isPostInWorkflow } = this.props;
      const { validationErrors, isLastStep, lastStepDecision, hideSignOff, signoffButtonText, assignActorLabel, dueDateLabel, displayDueDate, redirectingLoader, 
         stepSpinner, assigneeSpinner, submitSpinner, submitButtonDisable } = this.state;

      if ( hideSignOff && redirectingLoader === 'show' ) {
         return (
            <div>
               <PanelBody>
                  { __( 'redirecting...', 'oasisworkflow' ) }
               </PanelBody>               
            </div>
         )
      }

      // post is not in workflow anymore, so return empty
      if ( ! isPostInWorkflow || hideSignOff ) {
          return "";
       }

      return (
         <PanelBody ref={ this.signoffPanelRef } initialOpen={true} title={ signoffButtonText }>
            <form className="reusable-block-edit-panel">
               {validationErrors.length !== 0 ?
                  (<div id="owf-error-message" className="notice notice-error is-dismissible">
                     {validationErrors.map(error =>
                        <p key={error}>{error}</p>
                     )}
                  </div>) : ""
               }
               <SelectControl
                  label={ __('Action', 'oasisworkflow') + ':' }
                  value={this.state.selectedAction}
                  options={this.state.actions}
                  onChange={this.getSignoffSteps.bind(this)}
               />
               { isLastStep ? 
                  <SignoffLastStep 
                     stepDecision={lastStepDecision}
                     handleResponse={this.props.handleResponse} 
                  /> 
                  :
                  (
                     <div>
                        <div className="owf-spinner">
                           { stepSpinner == "show" ?
                              (
                                 <Spinner />
                              ) : ""
                           }
                        </div>
                        <SelectControl
                           label={ __( 'Step', 'oasisworkflow' ) + ':' }
                           value={this.state.selectedStep}
                           options={this.state.signoffSteps}
                           onChange={this.getSelectedStepDetails.bind(this)}
                        />
                        <TaskPriorities
                           value={this.state.selectedPriority}
                           onChange={this.handleOnPriorityChange.bind(this)}
                        />
                        <div>
                           <div className="owf-spinner">
                              { assigneeSpinner == "show" && ( this.state.assignToAll == false ) ?
                                 (
                                    <Spinner />
                                 ) : ""
                              }
                           </div>
                           {!this.state.assignToAll ?
                              (
                                 <SelectControl
                                    multiple
                                    label={ assignActorLabel + ':' }
                                    value={this.state.selectedAssignees}
                                    options={this.state.assignee}
                                    onChange={(selectedAssignees) => this.setState({ selectedAssignees })}
                                 />
                              ) : ""
                           }
                        </div>
                        { displayDueDate !== "" ? (
                           <PanelRow className="edit-post-post-schedule">
                           <label>{ dueDateLabel + ':' } </label>
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
                                          <OWDueDateLabel />
                                       </Button>
                                    </Fragment>
                                 )}
                                 renderContent={() => <OWDueDatePicker />}
                              />
                           </PanelRow>
                           ) : ""
                        }
                        <PanelRow>
                           <TextareaControl
                              label={ __( 'Comments', 'oasisworkflow') + ':' }
                              value={this.state.comments}
                              onChange={(comments) => this.setState({ comments })}
                           />
                        </PanelRow>
                        <PanelRow>
                           <Button
                              isPrimary
                              isLarge
                              isBusy={isSaving}
                              focus="true"
                              disabled = {submitButtonDisable}
                              onClick={this.handleSignoff.bind(this)}
                           >
                              { signoffButtonText }
                           </Button>
                           <div className="owf-spinner">
                              { submitSpinner == "show" ?
                                 (
                                    <Spinner />
                                 ) : ""
                              }
                           </div>
                        </PanelRow>
                     </div>
                  ) 
               }  
            </form>
         </PanelBody>
      )
   }
}

export default compose([
   withSelect((select) => {
      const { getCurrentPostId, getEditedPostAttribute } = select('core/editor');
      const { getDueDate, getOWSettings, getPostInWorkflow } = select('plugin/oasis-workflow');
      return {
         postId: getCurrentPostId(),
         postMeta: getEditedPostAttribute('meta'),
         dueDate: getDueDate(),
         owSettings: getOWSettings(),
         isPostInWorkflow: getPostInWorkflow()
      };
   }),
   withDispatch((dispatch) => ({
      onSave: dispatch('core/editor').savePost,
      setDueDate: dispatch('plugin/oasis-workflow').setDueDate
   }))
])(Signoff);