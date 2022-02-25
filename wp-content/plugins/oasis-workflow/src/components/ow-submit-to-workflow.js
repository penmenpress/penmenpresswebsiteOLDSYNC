/**
 * WordPress Dependencies
 */
const { Fragment, Component, createRef } = wp.element;

const { __ } = wp.i18n;
const { PanelBody, PanelRow, SelectControl, Dropdown, TextareaControl, Button, Spinner } = wp.components;

const { PostScheduleLabel, PostSchedule } = wp.editor;
const { pick } = lodash;
const { withSelect, withDispatch } = wp.data;
const { compose } = wp.compose;
const { dateI18n, __experimentalGetSettings } = wp.date;

/**
 * Internal dependencies
 */
import OWDueDatePicker from "./ow-due-date-picker";
import OWDueDateLabel from "./ow-due-date-label";
import TaskPriorities from "./ow-task-priority-select-control";
import WorkflowSelectControl from "./ow-workflow-select-control";
import { getStepAssignees } from "../util";

const settings = __experimentalGetSettings();

export class SubmitToWorkflow extends Component {
    constructor() {
        super(...arguments);

        this.submitToWorkflowPanelRef = createRef();

        // Set default first step dropdown state
        let firstSteps = [];
        firstSteps.push({ label: "", value: "" });

        this.state = {
            workflowButtonText: __("Submit to Workflow", "oasisworkflow"),
            assignActorLabel: __("Assign Actor(s)", "oasisworkflow"),
            publishDateLabel: __("Publish Date", "oasisworkflow"),
            dueDateLabel: __("Due Date", "oasisworkflow"),
            displayPublishDate: "",
            displayDueDate: "",
            selectedWorkflow: "",
            firstSteps: firstSteps,
            selectedFirstStep: "",
            selectedPriority: "2normal",
            assignee: [],
            selectedAssignees: [],
            publishDate: dateI18n(settings.formats.datetimeAbbreviated, new Date()),
            comments: "",
            assignToAll: false,
            validationErrors: [],
            hideForm: false,
            redirectingLoader: "hide",
            stepSpinner: "hide",
            assigneeSpinner: "hide",
            submitSpinner: "hide",
            submitButtonDisable: false
        };
    }

    componentDidMount() {
        let customWorkflowTerminology = this.props.owSettings.terminology_settings.oasiswf_custom_workflow_terminology;
        let workflowSettings = this.props.owSettings.workflow_settings;

        if (customWorkflowTerminology) {
            let workflowButtonText = customWorkflowTerminology.submitToWorkflowText;
            let assignActorLabel = customWorkflowTerminology.assignActorsText;
            let publishDateLabel = customWorkflowTerminology.publishDateText;
            let dueDateLabel = customWorkflowTerminology.dueDateText;
            this.setState({
                workflowButtonText,
                assignActorLabel,
                publishDateLabel,
                dueDateLabel
            });
        }

        if (workflowSettings) {
            let displayPublishDate = workflowSettings.oasiswf_publish_date_setting;
            let displayDueDate = workflowSettings.oasiswf_default_due_days;

            // set the default due date by using the workflow settings
            let dueDate = new Date();
            if (displayDueDate !== "") {
                dueDate.setDate(dueDate.getDate() + parseInt(displayDueDate));
            }
            this.props.setDueDate({ dueDate: dueDate });
            
            this.setState({
                displayPublishDate,
                displayDueDate
            });
        }
    }

    /**
     * Get First Step of the selected workflow
     * @param {*} workflowId
     */
    getFirstStep(workflowId) {
        let postId = this.props.postId;

        // Set selected workflow
        this.setState({
            selectedWorkflow: workflowId,
            validationErrors: [],
            stepSpinner: "show",
            assigneeSpinner: "show"
        });

        wp.apiFetch({
            path: "/oasis-workflow/v1/workflows/submit/firstStep/workflowId=" + workflowId + "/postId=" + postId,
            method: "GET"
        }).then(
            (stepdata) => {
                let firstStepId = stepdata.step_id;
                let firstStepLabel = stepdata.step_label;
                let firstSteps = [];
                let availableAssignees = [];
                let assignToAll = stepdata.assign_to_all === 1 ? true : false;
                let errors = [];
                firstSteps.push({ label: firstStepLabel, value: firstStepId });

                // set the default due date by using the workflow settings
                let dueDate = new Date();
                if (stepdata.due_days) {
                    dueDate.setDate(dueDate.getDate() + parseInt(stepdata.due_days));
                }
                this.props.setDueDate({ dueDate: dueDate });

                let assignees = stepdata.users;

                // Display Validation Message if no user found for the step
                if (assignees.length === 0) {
                    errors.push(__("No users found to assign the task.", "oasisworkflow"));
                    this.setState({
                        firstSteps: firstSteps,
                        selectedFirstStep: firstStepId,
                        validationErrors: errors,
                        assignee: []
                    });

                    // scroll to the top, so that the user can see the error
                    this.submitToWorkflowPanelRef.current.scrollIntoView();

                    return;
                }

                // Set and Get Assignees from the util function
                let stepAssignees = getStepAssignees({ assignees: assignees, assignToAll: assignToAll });
                availableAssignees = stepAssignees.availableAssignees;

                this.setState({
                    firstSteps: firstSteps,
                    selectedFirstStep: firstStepId,
                    assignee: availableAssignees,
                    selectedAssignees: stepAssignees.selectedAssignees,
                    assignToAll: assignToAll,
                    stepSpinner: "hide",
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
     * validate submit to workflow
     * @param {Object} data
     */
    validateSubmitToWorkflow(data) {
        const errors = [];
        let current_date = new Date();
        current_date = moment(current_date).format("YYYY-MM-DD");
        let due_date = moment(data.due_date).format("YYYY-MM-DD");

        let selected_workflow = this.state.selectedWorkflow;
        let selected_step = this.state.selectedFirstStep;

        if (selected_workflow === "") {
            errors.push(__("Please select a workflow.", "oasisworkflow"));
        }

        if (selected_step === "") {
            errors.push(__("Please select a step.", "oasisworkflow"));
        }

        if (data.due_date === "") {
            errors.push(__("Please enter a due date.", "oasisworkflow"));
        }

        if (data.due_date !== "" && moment(current_date).isAfter(due_date) == true) {
            errors.push(__("Due date must be greater than the current date.", "oasisworkflow"));
        }

        if (data.assignees.length === 0 && !this.state.assignToAll) {
            errors.push(__("No assigned actor(s).", "oasisworkflow"));
        }

        return errors;
    }

    // Submit to workflow - form submit
    async handleSubmitToWorkflow(event) {
        this.setState({
            submitSpinner: "show",
            submitButtonDisable: true
        });

        let form_data = {
            post_id: this.props.postId,
            step_id: this.state.selectedFirstStep,
            priority: this.state.selectedPriority,
            assignees: this.state.selectedAssignees,
            due_date: this.props.dueDate,
            publish_date: this.props.publishDate,
            comments: this.state.comments
        };

        await this.props.onSave();

        const errors = this.validateSubmitToWorkflow(form_data);

        if (errors.length > 0) {
            this.setState({
                validationErrors: errors,
                submitSpinner: "hide",
                submitButtonDisable: false
            });
            // scroll to the top, so that the user can see the error
            this.submitToWorkflowPanelRef.current.scrollIntoView();
            return;
        }

        this.setState({
            validationErrors: []
        });

        // TODO: introducing a delay to allow the post to be saved and then invoke the submit to workflow.
        var that = this;
        setTimeout(function () {
            that.invokeSubmitToWorkflowAPI(form_data);
        }, 500);
    }

    invokeSubmitToWorkflowAPI(form_data) {
        wp.apiFetch({ path: "/oasis-workflow/v1/workflows/submit/", method: "POST", data: form_data }).then(
            (submitResponse) => {
                this.setState({
                    hideForm: true,
                    redirectingLoader: "show"
                });
                // Handle redirect
                if (submitResponse.redirect_link !== "") {
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
        const {
            isSaving,
            isCurrentPostPublished,
            isCurrentPostScheduled,
            postStatus,
            isPostInWorkflow,
            postMeta
        } = this.props;
        const {
            validationErrors,
            hideForm,
            workflowButtonText,
            assignActorLabel,
            publishDateLabel,
            dueDateLabel,
            displayPublishDate,
            displayDueDate,
            redirectingLoader,
            stepSpinner,
            assigneeSpinner,
            submitSpinner,
            submitButtonDisable
        } = this.state;

        if (hideForm && redirectingLoader === "show") {
            return (
                <div>
                    <PanelBody>{__("redirecting...", "oasisworkflow")}</PanelBody>
                </div>
            );
        }

        if (
            (postMeta && postMeta._oasis_is_in_workflow == "1") || // post is in another workflow
            isPostInWorkflow ||
            hideForm ||
            isCurrentPostPublished || // a new post is published
            isCurrentPostScheduled
        ) {
            // a new post is scheduled
            return "";
        }

        return (
            <PanelBody ref={this.submitToWorkflowPanelRef} initialOpen={true} title={workflowButtonText}>
                <form className="reusable-block-edit-panel">
                    {validationErrors.length !== 0 ? (
                        <div id="owf-error-message" className="notice notice-error is-dismissible">
                            {validationErrors.map((error) => (
                                <p key={error}>{error}</p>
                            ))}
                        </div>
                    ) : (
                        ""
                    )}
                    <WorkflowSelectControl
                        value={this.state.selectedWorkflow}
                        onChange={this.getFirstStep.bind(this)}
                    />
                    <div className="owf-spinner">{stepSpinner == "show" ? <Spinner /> : ""}</div>
                    <SelectControl
                        label={__("Step", "oasisworkflow") + ":"}
                        value={this.state.selectedFirstStep}
                        options={this.state.firstSteps}
                        onChange={(selectedFirstStep) => this.setState({ selectedFirstStep })}
                    />
                    <TaskPriorities
                        value={this.state.selectedPriority}
                        onChange={this.handleOnPriorityChange.bind(this)}
                    />
                    <div className="owf-spinner">
                        {assigneeSpinner == "show" && this.state.assignToAll == false ? <Spinner /> : ""}
                    </div>
                    {!this.state.assignToAll ? (
                        <SelectControl
                            multiple
                            className="ow-multi-select"
                            label={assignActorLabel + ":"}
                            value={this.state.selectedAssignees}
                            options={this.state.assignee}
                            onChange={(selectedAssignees) => this.setState({ selectedAssignees })}
                        />
                    ) : (
                        ""
                    )}

                    {displayPublishDate == "" ? (
                        <PanelRow className="edit-post-post-schedule">
                            <label>{publishDateLabel + ":"} </label>
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
                                renderContent={() => <PostSchedule />}
                            />
                        </PanelRow>
                    ) : (
                        ""
                    )}

                    {displayDueDate !== "" ? (
                        <PanelRow className="edit-post-post-schedule">
                            <label>{dueDateLabel + ":"} </label>
                            <Dropdown
                                position="bottom left"
                                contentClassName="edit-post-post-schedule__dialog owduedatepicker-dropdown"
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
                    ) : (
                        ""
                    )}

                    <PanelRow>
                        <TextareaControl
                            label={__("Comments", "oasisworkflow") + ":"}
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
                            onClick={this.handleSubmitToWorkflow.bind(this)}
                        >
                            {workflowButtonText}
                        </Button>
                        <div className="owf-spinner">{submitSpinner == "show" ? <Spinner /> : ""}</div>
                    </PanelRow>
                </form>
            </PanelBody>
        );
    }
}

export default compose([
    withSelect((select) => {
        const {
            getCurrentPostId,
            getEditedPostAttribute,
            isCurrentPostPublished,
            isCurrentPostScheduled,
            getCurrentPost
        } = select("core/editor");
        const { getDueDate, getOWSettings, getPostInWorkflow } = select("plugin/oasis-workflow");
        const { status, type } = getCurrentPost();
        return {
            postId: getCurrentPostId(),
            postMeta: getEditedPostAttribute("meta"),
            isCurrentPostPublished: isCurrentPostPublished(),
            isCurrentPostScheduled: isCurrentPostScheduled(),
            publishDate: getEditedPostAttribute("date"),
            dueDate: getDueDate(),
            owSettings: getOWSettings(),
            postStatus: status,
            isPostInWorkflow: getPostInWorkflow()
        };
    }),
    withDispatch((dispatch) => ({
        onSave: dispatch("core/editor").savePost,
        autosave: dispatch("core/editor").autosave,
        setDueDate: dispatch("plugin/oasis-workflow").setDueDate
    }))
])(SubmitToWorkflow);
