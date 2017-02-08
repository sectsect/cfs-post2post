# <img src="https://github-sect.s3-ap-northeast-1.amazonaws.com/logo.svg" width="28" height="auto"> CFS Post 2 Post

### Creates two way (bidirectional) relationships in [Custom Field Suite](https://wordpress.org/plugins/custom-field-suite/).  

This plugin does not create a new type of field or any admin interface. This plugin when used as explained below makes the existing CFS Relationship work bi-directionaly, automatically updating the relationship field on the other end of the relationship.  

This plugin is inspired by <img src="https://github-sect.s3-ap-northeast-1.amazonaws.com/github.svg" width="18" height="auto"> Hube2's [Post 2 Post for ACF](https://github.com/Hube2/acf-post2post)

## Requirements

* PHP 5.3+
* Activation [Custom Field Suite](https://wordpress.org/plugins/custom-field-suite/) Plugin.

## Installation

 1. `cd /path-to-your/wp-content/plugins/`
 2. `git clone git@github.com:sectsect/cfs-post2post.git`
 3. Activate the plugin through the 'Plugins' menu in WordPress.  

 That's it:ok_hand:

## TIP

* The field name must be the same on all posts. In other words if you want to have different post types be related then you must add a field with the same field name on both post types.
* If you want to apply to some existing posts, Resave the post.

## Overwrite Settings

If the field in a related post, whether it is a relationship field that has a maximum number of related posts, if the field in the related post already has the maximum number of values allowed then, by default, a new value will not be added. You can override this default by specifying overwrite settings.  

You can access the Overwrite setting by going to `Settings` -> `CFS Post 2 Post`.
And select overwrite type.

- `Do not overwrite`
- `First Element`
- `Last Element`

The value selected in the field is deleted and the new value is added to the end.

## NOTES for Developer

* This Plugin does not hosting on the [wordpress.org](https://wordpress.org/) repo in order to prevent a flood of support requests from wide audience.

## Change log  

 * **1.0.0** - Initial Release

## License
See [LICENSE](https://github.com/sectsect/cfs-post2post/blob/master/LICENSE) file.

## Related Plugin
I have some plugins for [Custom Field Suite](https://wordpress.org/plugins/custom-field-suite/).  
#### <img src="https://github-sect.s3-ap-northeast-1.amazonaws.com/github.svg" width="22" height="auto"> [CFS Loop Field Query](https://github.com/sectsect/cfs-loop-field-query)
