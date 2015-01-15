<?php 

// Outputs a css file to enqueue to WP head
header('Content-type: text/css');

// include_once ('wp_guwMetaBoxes.php');

echo "
.guwToolTip {
  border-radius: 4px;
  box-shadow: 0 0 16px 0 #000;
  font-size: 14px;
  font-weight: 700;
  height: auto;
  left: -45px;
  padding-bottom: 12px;
  position: absolute;
  top: 28px;
  width: 250px;
  z-index: 9999;
}

.guwToolTipPic {
  border: 2px solid #fff;
  box-shadow: 0 0 12px 0 #000;
  height: 90%;
  margin: 0 auto;
  overflow: hidden;
  width: 90%;
}

.hoverImgWord {
	position: relative !important;
	cursor:pointer;
}

.guwToolTipWrap:hover .guwToolTip {
	display:block !important;
	position:absolute !important;
}

.guwToolTipWrap {
  position: relative;
  display:inline-block;
}

.guwToolTip p {
	text-align:center;
}
";

?>

