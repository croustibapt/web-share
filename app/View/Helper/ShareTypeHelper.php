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
}