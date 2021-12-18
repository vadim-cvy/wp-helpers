<?php

namespace JMCG\inc\framework\posts\fields\acf\field;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Incapsulates ACF fields common helpers.
 */
class Fields
{
    /**
     * Returns field by specified field key.
     *
     * @param   string  $field_key      Field key.
     * @param   string  $context        ACF context. Possible values:
     *                                  "{post id}", "term_{term id}", "user_{user id}".
     * @param   boolean $validate_exist If error should be triggered in case field does not exist.
     *                                  True - trigger error, false - avoid error.
     * @return  Field                   Field instance.
     */
    public static function get_by_key( string $field_key, string $context = '', bool $validate_exist = false ) : Field
    {
        $field = static::create_using_key( $field_key, $context );

        if ( $validate_exist )
        {
            $field->validate_exists();
        }

        return $field;
    }

    /**
     * Returns field by specified field id (field post id).
     *
     * @param   int     $field_id       Field id (field post id).
     * @param   string  $context        ACF context. Possible values:
     *                                  "{post id}", "term_{term id}", "user_{user id}".
     * @param   boolean $validate_exist If error should be triggered in case field does not exist.
     *                                  True - trigger error, false - avoid error.
     * @return  Field                   Field instance.
     */
    public static function get_by_id( int $field_id, string $context = '', bool $validate_exist = false ) : Field
    {
        $field_key = static::get_key_by_id( $field_id );

        return static::get_by_key( $field_key, $context, $validate_exist );
    }

    /**
     * Returns field by specified field name.
     *
     * @param   string  $field_name     Field name.
     * @param   string  $context        ACF context. Possible values:
     *                                  "{post id}", "term_{term id}", "user_{user id}".
     * @param   boolean $validate_exist If error should be triggered in case field does not exist.
     *                                  True - trigger error, false - avoid error.
     * @return  Field                   Field instance.
     */
    public static function get_by_name( string $field_name, string $context = '', bool $validate_exist = false ) : Field
    {
        $field_object = get_field_object( $field_name, $context );

        return static::get_by_key( $field_object['key'], $context, $validate_exist );
    }

    /**
     * Returns field by specified setting.
     *
     * @param   string  $setting_name   Field setting name.
     * @param   mixed   $setting_value  Field setting value to filter against.
     *                                  All fields having any value of the setting will be returned
     *                                  if $setting_value is not passed.
     * @return  Field                   Field instance.
     */
    public static function get_by_setting( string $setting_name, $setting_value = null ) : array
    {
        $fields = [];

        foreach ( static::get_all() as $field )
        {
            $original = $field->get_original();

            if (
                ! isset( $original[ $setting_name ] ) ||
                ( isset( $setting_value ) && $original[ $setting_name ] !== $setting_value )
            )
            {
                continue;
            }

            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * Returs all fields registered on the site.
     *
     * @param   string  $context  ACF context. Possible values:
     *                            "{post id}", "term_{term id}", "user_{user id}".
     * @return array<Field> Fields instances.
     */
    public static function get_all( string $context = '' ) : array
    {
        $fields = [];

        foreach ( static::get_fields_posts_query()->posts as $field_post )
        {
            $field_key = $field_post->post_name;

            $fields[] = static::create_using_key( $field_key, $context );
        }

        return $fields;
    }

    /**
     * Returns field key by specified field id (field post id).
     *
     * @param   string $field_id    Field id (post id).
     * @return  string              Field key.
     */
    protected static function get_key_by_id( string $field_id ) : string
    {
        $field_post = get_post( $field_id );

        if ( empty( $field_post ) )
        {
            return '';
        }

        return $field_post->post_name;
    }

    /**
     * Returns WP_Query targeted on 'acf-field' post type.
     *
     * @param   array $args     WP_Query custom args.
     * @return  \WP_Query       WP_Query targeted on 'acf-field' post type.
     */
    protected static function get_fields_posts_query( $args = [] ) : \WP_Query
    {
        $default_args = [
            'post_type'      => 'acf-field',
            'posts_per_page' => -1,
        ];

        $args = array_merge( $default_args, $args );

        return new \WP_Query( $args );
    }

    /**
     * Creates field instance.
     *
     * Is useful if you have a custom class that inherits Field class and you want
     * Fields methods to return fields wrapped with your custom class.
     * In such case you may create child class of Fields and overwrite this method.
     *
     * @param   string $field_key   Field key.
     * @param   string  $context    ACF context. Possible values:
     *                              "{post id}", "term_{term id}", "user_{user id}".
     * @return  Field               Field instance.
     */
    protected static function create_using_key( string $field_key, string $context = '' ) : Field
    {
        $field = new Field( $field_key );

        if ( $context )
        {
            $field->set_context( $context );
        }

        return $field;
    }
}