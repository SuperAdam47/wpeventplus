<?php
echo $before_widget;
if ($title) {
    echo $before_title . $title . $after_title;
}
?>
<style type="text/css">
    .timing
    {
        font-family: "Helvetica Neue Bold", arial, helvetica, sans-serif;
        font-size: 100%;
        border-left: 1px solid #d3d3d3;
        color: #333;
    }
    .timing .time-cont{
        display: block;
        margin: 0 auto;
        width: 245px;
    }
    .timing h1
    {
        margin: 0;
        font-weight: normal;
    }
    .timing time.icon
    {
        font-size: 1em; /* change icon size */
        display: block;
        position: relative;
        width: 60px;
        height: 60px;
        background-color: #fff;
        margin: 1em auto;
        border-radius: 0.6em;
        box-shadow: 0 1px 0 #bdbdbd, 0 2px 0 #fff, 0 3px 0 #bdbdbd, 0 4px 0 #fff, 0 5px 0 #bdbdbd, 0 0 0 1px #bdbdbd;
        overflow: hidden;
        -webkit-backface-visibility: hidden;
        -webkit-transform: rotate(0deg) skewY(0deg);
        -webkit-transform-origin: 50% 10%;
        transform-origin: 50% 10%;
    }
    .timing time.icon *
    {
        display: block;
        width: 100%;
        /* font-size: 14px; */
        font-weight: bold;
        font-style: normal;
        text-align: center;
    }
    .timing time.icon strong
    {
        position: absolute;
        top: 0;
        padding: 0;
        color: #fff;
        background-color: #999999;
        border-bottom: 1px dashed #999999;
        box-shadow: 0 2px 0 #999999;
    }
    .timing time.icon em
    {
        position: absolute;
        bottom: 0.3em;
        color: #999999;
        font-size: 10px;
    }
    .timing time.icon span
    {
        width: 100%;
        font-size: 15px;
        letter-spacing: -0.05em;
        padding-top: 24px;
        color: #2f2f2f;
    }
    .timing time.icon:hover, time.icon:focus
    {
        -webkit-animation: swing 0.6s ease-out;
        animation: swing 0.6s ease-out;
    }
    .timing .eve-sap{
        position: relative;
        top: 50px;
        float: left;
        padding: 0 15px;
        font-size: 50px;
        color: #999;
    }
    .timing .eve-start,
    .timing .eve-end{
        float: left;
    }
    .timing .eve-start p,
    .timing .eve-end p{
        margin-bottom: 0;
        font-size: 14px;
        text-align: center;
        color: #999;
    }
    @-webkit-keyframes swing {
        0%   { -webkit-transform: rotate(0deg)  skewY(0deg); }
        20%  { -webkit-transform: rotate(12deg) skewY(4deg); }
        60%  { -webkit-transform: rotate(-9deg) skewY(-3deg); }
        80%  { -webkit-transform: rotate(6deg)  skewY(-2deg); }
        100% { -webkit-transform: rotate(0deg)  skewY(0deg); }
    }
    @keyframes swing {
        0%   { transform: rotate(0deg)  skewY(0deg); }
        20%  { transform: rotate(12deg) skewY(4deg); }
        60%  { transform: rotate(-9deg) skewY(-3deg); }
        80%  { transform: rotate(6deg)  skewY(-2deg); }
        100% { transform: rotate(0deg)  skewY(0deg); }
    }
</style>
<ul style="max-width: 500px;  border: 1px solid #cdcdcd; border-radius:2px; padding:0;">
    <?php
    echo $events_list;
    ?></ul>
<?php
echo $after_widget;
