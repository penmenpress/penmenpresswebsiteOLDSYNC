/**
 * WordPress Dependencies
 */
const { __ } = wp.i18n;

const WorkflowRevisionFeatureMessage = props => (
   <div>
      {__( 'Workflow support for published posts is available with Pro version. ', 'oasisworkflow' ) }  
		<a target="_blank" href="https://www.oasisworkflow.com/documentation/working-with-workflows/revise-published-content">Click here</a>
		{__(' for more information.', 'oasisworkflow' )}
   </div>
);

export default WorkflowRevisionFeatureMessage;