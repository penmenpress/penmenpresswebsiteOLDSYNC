<?php
/**
* Tag Groups
*
* @package    Tag Groups
* @author     Christoph Amthor
* @copyright  2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license    see official vendor website
* @since      1.24.0
*
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*
*/

if ( ! class_exists( 'TagGroups_Process' ) ) {

  /**
  * Background workers for the progress bar
  *
  */
  class TagGroups_Process {

    /**
    * Receives the Ajax call to process chunks of long tasks, to avoid timeouts
    *
    *
    * @param void
    * @return void
    */
    public static function tg_ajax_process()
    {

      global $tag_group_groups, $tag_group_terms;

      $affected = 0;

      $error = false;

      if ( isset( $_REQUEST['nonce'] ) ) {

        $nonce = $_REQUEST['nonce'];

      }

      if ( ! wp_verify_nonce( $nonce, 'tag-groups-process-nonce' ) ) {

        die( 'Access denied.' );

      }

      if ( isset( $_REQUEST['task'] ) ) {

        $task = $_REQUEST['task'];

      } else {

        $error = true;

      }

      if ( isset( $_REQUEST['offset'] ) ) {

        $offset = (int) $_REQUEST['offset'];

      } else {

        $error = true;

      }

      if ( isset( $_REQUEST['length'] ) ) {

        $length = (int) $_REQUEST['length'];

      } else {

        $error = true;

      }

      if ( isset( $_REQUEST['languagecode'] ) ) {

        $language_code = sanitize_title( $_REQUEST['languagecode'] );

      } else {

        $language_code = '';

      }

      $response = new WP_Ajax_Response;

      if ( ! $error ) {

        switch ( $task ) {

          case 'migratetermmeta':

          $affected = $tag_group_terms->convert_to_term_meta( false, $offset, $length );

          break;

          case 'fixgroups':

          $affected = $tag_group_groups->check_fix_groups( false, $offset, $length );

          break;

          case 'fixmissinggroups':

          $affected = $tag_group_terms->remove_missing_groups( false, $offset, $length );

          break;

          default:

          $error = true;

          break;

        }

      }

      if ( $error ) {

        $response->add(
          array(
            'data' => 'error',
            'supplemental' => array(
              'done' => json_encode(0),
              'affected' => json_encode(0),
            )
          )
        );

        $response->send();

        wp_die();

      }


      $response->add(
        array(
          'data' => 'success',
          'supplemental' => array(
            'done' => json_encode(1),
            'affected' => json_encode( $affected ),
          )
        )
      );

      $response->send();

      wp_die();

    }


    /**
    * Gets the total number of things to do for a particular task
    *
    * @param string $task
    * @return int
    */
    public static function get_task_total( $task, $language_code = null )
    {

      global $tag_group_groups, $tag_group_terms;

      switch ( $task ) {

        case 'migratetermmeta':

        return $tag_group_terms->convert_to_term_meta( true );


        case 'fixgroups':

        $affected = $tag_group_groups->check_fix_groups( true );

        break;

        case 'fixmissinggroups':

        return $tag_group_terms->remove_missing_groups( true );

        break;
        break;

        case 'fixgroups':

        $affected = $tag_group_groups->check_fix_groups( true );

        break;

        case 'fixmissinggroups':

        return $tag_group_terms->remove_missing_groups( true );

        break;

        default:

        return false;

        break;

      }

      return 0;

    }


  }

}
