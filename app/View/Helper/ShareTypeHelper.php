<?php

define('CARROT_COLOR', '#e67e22');
define('BELIZE_HOLE_COLOR', '#2980b9');
define('GREEN_SEA_COLOR', '#16a085');
define('NEPHRITIS_COLOR', '#27ae60');
define('WISTERIA_COLOR', '#8e44ad');
define('POMEGRANATE_COLOR', '#c0392b');
define('WET_ASPHALT_COLOR', '#34495e');
define('ASBESTOS_COLOR', '#7f8c8d');
define('CONCRETE_COLOR', '#95a5a6');

App::uses('AppHelper', 'View/Helper');

class ShareTypeHelper extends AppHelper {
    public function shareTypeColor($shareTypeCategory = NULL) {
        if ($shareTypeCategory == "food") {
            return CARROT_COLOR;
        } else if ($shareTypeCategory == "hightech") {
            return BELIZE_HOLE_COLOR;
        } else if ($shareTypeCategory == "audiovisual") {
            return GREEN_SEA_COLOR;
        } else if ($shareTypeCategory == "recreation") {
            return NEPHRITIS_COLOR;
        } else if ($shareTypeCategory == "mode") {
            return WISTERIA_COLOR;
        } else if ($shareTypeCategory == "house") {
            return POMEGRANATE_COLOR;
        } else if ($shareTypeCategory == "service") {
            return WET_ASPHALT_COLOR;
        } else if ($shareTypeCategory == "other") {
            return ASBESTOS_COLOR;
        } else {
            return CONCRETE_COLOR;
        }
    }

    public function shareTypeLabel($shareTypeCategory = NULL) {
        if ($shareTypeCategory == "food") {
            return "Nourriture";
        } else if ($shareTypeCategory == "hightech") {
            return "High-Tech";
        } else if ($shareTypeCategory == "audiovisual") {
            return "Audiovisuel";
        } else if ($shareTypeCategory == "recreation") {
            return "Loisirs";
        } else if ($shareTypeCategory == "mode") {
            return "Mode";
        } else if ($shareTypeCategory == "house") {
            return "Maison";
        } else if ($shareTypeCategory == "service") {
            return "Service";
        } else if ($shareTypeCategory == "other") {
            return "Autre";
        } else {
            return CONCRETE_COLOR;
        }
    }
    
    public function shareTypeIcon($shareTypeCategory = NULL, $shareType = NULL) {
        if ($shareTypeCategory == "food") {
            if ($shareType == "pizza") {
                return '<i class="icon ion-pizza"></i>';
            } else if ($shareType == "snack") {
                return '<i class="fa fa-coffee"></i>';
            } else {
                return '<i class="fa fa-cutlery"></i>';
            }
        } else if ($shareTypeCategory == "hightech") {
            if ($shareType == "component") {
                return '<i class="fa fa-keyboard-o"></i>';
            } else if ($shareType == "computer") {
                return '<i class="fa fa-desktop"></i>';
            } else if ($shareType == "phone") {
                return '<i class="fa fa-mobile"></i>';
            } else if ($shareType == "storage") {
                return '<i class="fa fa-hdd-o"></i>';
            } else if ($shareType == "application") {
                return '<i class="fa fa-tablet"></i>';
            } else {
                return '<i class="fa fa-laptop"></i>';
            }
        } else if ($shareTypeCategory == "audiovisual") {
            if ($shareType == "picture") {
                return '<i class="fa fa-picture-o"></i>';
            } else if ($shareType == "sound") {
                return '<i class="fa fa-volume-up"></i>';
            } else if ($shareType == "photo") {
                return '<i class="fa fa-camera-retro"></i>';
            } else if ($shareType == "disc") {
                return '<i class="fa fa-microphone"></i>';
            } else if ($shareType == "game") {
                return '<i class="fa fa-gamepad"></i>';
            } else {
                return '<i class="fa fa-headphones"></i>';
            }
        } else if ($shareTypeCategory == "recreation") {
            if ($shareType == "cinema") {
                return '<i class="fa fa-film"></i>';
            } else if ($shareType == "show") {
                return '<i class="fa fa-ticket"></i>';
            } else if ($shareType == "game") {
                return '<i class="fa fa-puzzle-piece"></i>';
            } else if ($shareType == "book") {
                return '<i class="fa fa-book"></i>';
            } else if ($shareType == "outdoor") {
                return '<i class="fa fa-sun-o"></i>';
            } else if ($shareType == "sport") {
                return '<i class="fa fa-futbol-o"></i>';
            } else if ($shareType == "auto") {
                return '<i class="fa fa-car"></i>';
            } else if ($shareType == "moto") {
                return '<i class="fa fa-motorcycle"></i>';
            } else if ($shareType == "music") {
                return '<i class="fa fa-music"></i>';
            } else if ($shareType == "pet") {
                return '<i class="fa fa-paw"></i>';
            } else {
                return '<i class="fa fa-paint-brush"></i>';
            }
        } else if ($shareTypeCategory == "mode") {
            if ($shareType == "man") {
                return '<i class="fa fa-male"></i>';
            } else if ($shareType == "woman") {
                return '<i class="fa fa-female"></i>';
            } else if ($shareType == "mixte") {
                return '<i class="icon ion-ios-body"></i>';
            } else if ($shareType == "child") {
                return '<i class="fa fa-child"></i>';
            } else if ($shareType == "jewelry") {
                return '<i class="fa fa-diamond"></i>';
            } else {
                return '<i class="icon ion-tshirt"></i>';
            }
        } else if ($shareTypeCategory == "house") {
            if ($shareType == "furniture") {
                return '<i class="fa fa-archive"></i>';
            } else if ($shareType == "kitchen") {
                return '<i class="icon ion-knife"></i>';
            } else if ($shareType == "diy") {
                return '<i class="fa fa-wrench"></i>';
            } else {
                return '<i class="fa fa-home"></i>';
            }
        } else if ($shareTypeCategory == "service") {
            if ($shareType == "travel") {
                return '<i class="fa fa-suitcase"></i>';
            } else if ($shareType == "hotel") {
                return '<i class="fa fa-bed"></i>';
            } else if ($shareType == "wellness") {
                return '<i class="fa fa-smile-o"></i>';
            } else {
                return '<i class="fa fa-briefcase"></i>';
            }
        } else if ($shareTypeCategory == "other") {
            return '<i class="fa fa-ellipsis-h"></i>';
        } else {
            return '<i class="fa fa-question-circle"></i>';
        }
    }
}