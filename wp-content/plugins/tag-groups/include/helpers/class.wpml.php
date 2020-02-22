<?php
/**
* Tag Groups
*
* @package     Tag Groups Premium
* @author      Christoph Amthor
* @copyright   2017 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     see official vendor website
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

if ( ! class_exists('TagGroups_WPML') ) {

  class TagGroups_WPML {


    // TODO: add unit test
    /**
    * Get the transient name for tag_groups_group_terms
    *
    * In case we use the WPML plugin: consider the language
    *
    * @param void
    * @return string
    */
    public static function get_tag_groups_group_terms_transient_name() {

      if ( defined( 'ICL_LANGUAGE_CODE' ) ) {

        return 'tag_groups_group_terms-' . (string) ICL_LANGUAGE_CODE;

      } else {

        return 'tag_groups_group_terms';

      }

    }


    // TODO: add unit test
    /**
    * Get the transient name for tag_groups_post_counts
    *
    * In case we use the WPML plugin: consider the language
    * Use $language if provided, else use current language
    *
    * @param string $language
    * @return string
    */
    public static function get_tag_groups_post_counts_transient_name( $language_code = null ) {

      if ( ! empty( $language_code ) ) {

        return 'tag_groups_post_counts-' . (string) $language_code;

      }

      if ( defined( 'ICL_LANGUAGE_CODE' ) ) {

          return 'tag_groups_post_counts-' . (string) ICL_LANGUAGE_CODE;

      } else {

        return 'tag_groups_post_counts';

      }

    }

  }

}
