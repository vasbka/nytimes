<?php

namespace Includes\Controllers\Site;

use Includes\Models\Site\NYTimesLastNewsShortCodeModel;

class NYTimesLastNewsController
extends NYTimesShortCodesController
{

    public $model;
    private $countNews;
    public function __construct()
    {
        parent::__construct();
        $this->model = NYTimesLastNewsShortCodeModel::newInstance();
    }

    public function initShortCode()
    {
        // TODO: Implement initShortCode() method.
        add_shortcode('NYTimes',array($this,'action'));
    }

    public function action($atts = array(), $content = '', $tag = '')
    {
        // TODO: Implement action() method.
        $atts = shortcode_atts( array(
            'category' => 'Arts',
            'period' => '7',
            'counter' => '1'
        ),$atts,$tag);
        $data = $this->model->getData($atts['category'],$atts['period']);
        $this->countNews = $atts['counter'];
        if($data == false)
            return false;
        return $this->render($data);
    }
    public function render($rez)
    {
//        ?><!--<pre>--><?//var_dump($rez);die;?><!--</pre>--><?//
        for($i = 0;$i<$this->countNews;$i++){
            if (array_key_exists(0, $rez)):?>
                <p id="NYTimesTitle">
                    <?php do_action('plugin_title');
                    echo ' : ' . $rez[$i]->title ?>
                </p>
                <?php if (array_key_exists('url', $rez[$i]->media[0]->{'media-metadata'}[2])): ?>
                    <img src="<?php echo $rez[$i]->media[0]->{'media-metadata'}[2]->url ?>">
                <?endif; ?>
                <p id="NYTimesSource">
                    <?php do_action('plugin_source');
                    echo ' : ' . $rez[$i]->source; ?>
                </p>
                <p id="NYTimesPublishDate">
                    <?php do_action('plugin_date');
                    echo ' : ' . $rez[$i]->{'published_date'} ?>
                </p>
                <p id="NYTimesAbstract">
                    <?php do_action('plugin_short');
                    echo " : " . $rez[$i]->abstract ?>
                </p>
                <a href="<? echo $rez[$i]->url ?>" id="NYTimesReadMore"> Read more. . . </a><br><br>
                <?php
            endif;
            if (!array_key_exists(0, $rez))
                echo 'No one post for last day.';
        }
    }


    public static function newInstance()
    {
        $instance = new self;
        return $instance;
    }

}