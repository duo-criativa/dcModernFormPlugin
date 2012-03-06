<?php
/**
 * sfWidgetFormFCBKcompleter represents an autocompleter input widget rendered by FCBKcomplete
 * (https://github.com/emposha/FCBKcomplete).
 *
 * This widget needs JQuery and FCBKcomplete to work.
 *
 * You also need to include the JavaScripts and stylesheets files returned by the getJavaScripts()
 * and getStylesheets() methods.
 *
 *
 * @package    duocriativa
 * @subpackage widget
 * @author     Pauo Ribeiro <paulo@duocriativa.com.br>
 * @version    SVN: $Id$
 */
class sfWidgetFormFCBKcompleter extends sfWidgetFormInput
{

  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * url:            The URL to call to get the choices to use (required)
   *  * config:         A JavaScript array that configures the JQuery FCBKcomplete widget
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('url');
    $this->addOption('value_callback');
    $this->addOption('config', array());

    // this is required as it can be used as a renderer class for sfWidgetFormChoice
    $this->addOption('choices');

    parent::configure($options, $attributes);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $valuesWithLabels = $this->getOption('value_callback') ? call_user_func($this->getOption('value_callback'), $value) : $value;

    $content = '';
    if(count($valuesWithLabels)){
      foreach($valuesWithLabels as $key=>$value){
        $content .= $this->renderContentTag('option', $value, array('value'=>$key, 'selected'=>'selected', 'class'=>'selected'));
      }
    }
    $config = $this->getOption('config');
    $config['json_url'] = $this->getOption('url');
    return $this->renderContentTag('select', $content, array_merge(array('name' => $name, 'value' => $value), $attributes))
        .
        sprintf(<<<EOF
<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery("#%s").fcbkcomplete(%s);
  });
</script>
EOF
          ,
          $this->generateId($name),
          json_encode($config)
        );
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('/dcModernFormPlugin/vendor/fcbkcomplete/style.css' => 'all');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return array('/dcModernFormPlugin/vendor/fcbkcomplete/jquery.fcbkcomplete.min.js');
  }
}

