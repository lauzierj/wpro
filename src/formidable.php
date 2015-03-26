<?php

add_action('frm_before_destroy_entry', 'delete_entry_uploads');
function delete_entry_uploads($entry_id) {
    $entry = FrmEntry::getOne($entry_id);

    $upload_fields = FrmField::getAll(array('fi.type' => 'file', 'fi.form_id' => $entry->form_id));
    foreach ( $upload_fields as $upload_field ) {
        $media_id = FrmEntryMeta::get_entry_meta_by_field($entry->id, $upload_field->id);
        if ( !$media_id && $entry->post_id ) { //if this is a post field, get the value
            if ( !isset($upload_field->field_options['post_field']) ) {
                $upload_field->field_options['post_field'] = '';
            }

            if ( !isset($upload_field->field_options['custom_field']) ) {
                $upload_field->field_options['custom_field'] = '';
            }

            if ( $upload_field->field_options['post_field'] ) {
                $media_id = FrmProEntryMetaHelper::get_post_value(
                    $entry->post_id, $upload_field->field_options['post_field'],
                    $upload_field->field_options['custom_field'], array('type' => 'file')
                );
            }
        }


        if ( !$media_id ) {
            continue;
        }

        $media_id = maybe_unserialize($media_id);
        if ( is_numeric($media_id) ) {
            wp_delete_attachment($media_id, true);
        } else if ( is_array($media_id) ) {
            foreach ( $media_id as $m ) {
                if ( is_numeric($m) ) {
                    wp_delete_attachment($m, true);
                }
            }
        }
        unset($upload_field, $media_id);
    }
}

?>