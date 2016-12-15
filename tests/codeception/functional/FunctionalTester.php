<?php //[STAMP] c72c14dd5ab8c96800aa2ca92371a2d6

// This class was automatically generated by build task
// You should not change it manually as it will be overwritten on next build
// @codingStandardsIgnoreFile


use WPCC\Module\WordPress;
use Codeception\Module\FunctionalHelper;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends \Codeception\Actor
{
   
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Checks user meta exists for an user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $user_id The user id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
     * Conditional Assertion: Test won't be stopped on fail
     * @see \WPCC\Module\WordPress::seeUserMetaFor()
     */
    public function canSeeUserMetaFor($user_id, $meta_key, $meta_value = null) {
        return $this->scenario->runStep(new \Codeception\Step\ConditionalAssertion('seeUserMetaFor', func_get_args()));
    }
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Checks user meta exists for an user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $user_id The user id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
     * @see \WPCC\Module\WordPress::seeUserMetaFor()
     */
    public function seeUserMetaFor($user_id, $meta_key, $meta_value = null) {
        return $this->scenario->runStep(new \Codeception\Step\Assertion('seeUserMetaFor', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Checks if user meta doesn't exists.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $user_id The user id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
     * Conditional Assertion: Test won't be stopped on fail
     * @see \WPCC\Module\WordPress::dontSeeUserMetaFor()
     */
    public function cantSeeUserMetaFor($user_id, $meta_key, $meta_value = null) {
        return $this->scenario->runStep(new \Codeception\Step\ConditionalAssertion('dontSeeUserMetaFor', func_get_args()));
    }
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Checks if user meta doesn't exists.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $user_id The user id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
     * @see \WPCC\Module\WordPress::dontSeeUserMetaFor()
     */
    public function dontSeeUserMetaFor($user_id, $meta_key, $meta_value = null) {
        return $this->scenario->runStep(new \Codeception\Step\Assertion('dontSeeUserMetaFor', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Checks a post meta exists for a post.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $post_id The post id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
     * Conditional Assertion: Test won't be stopped on fail
     * @see \WPCC\Module\WordPress::seePostMetaFor()
     */
    public function canSeePostMetaFor($post_id, $meta_key, $meta_value = null) {
        return $this->scenario->runStep(new \Codeception\Step\ConditionalAssertion('seePostMetaFor', func_get_args()));
    }
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Checks a post meta exists for a post.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $post_id The post id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
     * @see \WPCC\Module\WordPress::seePostMetaFor()
     */
    public function seePostMetaFor($post_id, $meta_key, $meta_value = null) {
        return $this->scenario->runStep(new \Codeception\Step\Assertion('seePostMetaFor', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Checks if post meta doesn't exists.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $post The post id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
     * Conditional Assertion: Test won't be stopped on fail
     * @see \WPCC\Module\WordPress::dontSeePostMetaFor()
     */
    public function cantSeePostMetaFor($post, $meta_key, $meta_value = null) {
        return $this->scenario->runStep(new \Codeception\Step\ConditionalAssertion('dontSeePostMetaFor', func_get_args()));
    }
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Checks if post meta doesn't exists.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param int $post The post id.
	 * @param string $meta_key The meta key to check.
	 * @param mixed $meta_value The meta value to check
     * @see \WPCC\Module\WordPress::dontSeePostMetaFor()
     */
    public function dontSeePostMetaFor($post, $meta_key, $meta_value = null) {
        return $this->scenario->runStep(new \Codeception\Step\Assertion('dontSeePostMetaFor', func_get_args()));
    }
}
