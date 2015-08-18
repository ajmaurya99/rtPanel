<!-- This file is used to markup the administration form of the widget. -->
<p>
  <label for="<?php echo $this->get_field_id( 'time_format' ); ?>">
    <?php _e( 'Time Format', $this->widget_slug ); ?>:
  </label>
  <select id="<?php echo $this->get_field_id( 'time_format' ); ?>"
    name="<?php echo $this->get_field_name( 'time_format' ); ?>"
    class="widefat">
    <?php $this->render_time_format( $instance ); ?>
  </select>
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'date_format' ); ?>">
    <?php _e( 'Date Format', $this->widget_slug ); ?>:
  </label>
  <select id="<?php echo $this->get_field_id( 'date_format' ); ?>"
    name="<?php echo $this->get_field_name( 'date_format' ); ?>"
    class="widefat">
    <?php $this->render_date_format( $instance ); ?>
  </select>
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'font_family' ); ?>">
    <?php _e( 'Font Family', $this->widget_slug ); ?>:
  </label>
  <select id="<?php echo $this->get_field_id( 'font_family' ); ?>"
    name="<?php echo $this->get_field_name( 'font_family' ); ?>"
    class="widefat">
    <?php $this->render_font_family( $instance ); ?>
  </select>
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'font_size' ); ?>">
    <?php _e( 'Font Size', $this->widget_slug ); ?>:
  </label>
  <select id="<?php echo $this->get_field_id( 'font_size' ); ?>"
    name="<?php echo $this->get_field_name( 'font_size' ); ?>"
    class="widefat">
    <?php $this->render_font_size( $instance ); ?>
  </select>
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'text_color' ); ?>">
    <?php _e( 'Text Color', $this->widget_slug ) ?>:
  </label>
  <input id="<?php echo $this->get_field_id( 'text_color' ); ?>"
    name="<?php echo $this->get_field_name( 'text_color' ); ?>"
    value="<?php echo $text_color; ?>"
    type="text" class="color-picker" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'background_color' ); ?>">
    <?php _e( 'Background Color', $this->widget_slug ) ?>:
  </label>
  <input id="<?php echo $this->get_field_id( 'background_color' ); ?>"
    name="<?php echo $this->get_field_name( 'background_color' ); ?>"
    value="<?php echo $background_color; ?>"
    type="text" class="color-picker" />
</p>