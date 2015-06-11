/**
 * Created by bleguelvouit on 09/06/15.
 */

//Main AngularJS app
var app = angular.module("app", []);

var CARROT_COLOR = '#e67e22';
var BELIZE_HOLE_COLOR = '#2980b9';
var GREEN_SEA_COLOR = '#16a085';
var NEPHRITIS_COLOR = '#27ae60';
var WISTERIA_COLOR = '#8e44ad';
var POMEGRANATE_COLOR = '#c0392b';
var WET_ASPHALT_COLOR = '#34495e';
var ASBESTOS_COLOR = '#7f8c8d';
var CONCRETE_COLOR = '#95a5a6';

/**
 * Method used to get an icon corresponding to a specific share type category and a specific share type
 * @param shareTypeCategory
 * @param shareType
 * @returns {string}
 */
function getMarkerIcon(shareTypeCategory, shareType) {
    if (shareTypeCategory == "food") {
        if (shareType == "pizza") {
            return 'icon ion-pizza';
        } else if (shareType == "snack") {
            return 'fa fa-coffee';
        } else {
            return 'fa fa-cutlery';
        }
    } else if (shareTypeCategory == "hightech") {
        if (shareType == "component") {
            return 'fa fa-keyboard-o';
        } else if (shareType == "computer") {
            return 'fa fa-desktop';
        } else if (shareType == "phone") {
            return 'fa fa-mobile';
        } else if (shareType == "storage") {
            return 'fa fa-hdd-o';
        } else if (shareType == "application") {
            return 'fa fa-tablet';
        } else {
            return 'fa fa-laptop';
        }
    } else if (shareTypeCategory == "audiovisual") {
        if (shareType == "picture") {
            return 'fa fa-picture-o';
        } else if (shareType == "sound") {
            return 'fa fa-volume-up';
        } else if (shareType == "photo") {
            return 'fa fa-camera-retro';
        } else if (shareType == "disc") {
            return 'fa fa-microphone';
        } else if (shareType == "game") {
            return 'fa fa-gamepad';
        } else {
            return 'fa fa-headphones';
        }
    } else if (shareTypeCategory == "recreation") {
        if (shareType == "cinema") {
            return 'fa fa-film';
        } else if (shareType == "show") {
            return 'fa fa-ticket';
        } else if (shareType == "game") {
            return 'fa fa-puzzle-piece';
        } else if (shareType == "book") {
            return 'fa fa-book';
        } else if (shareType == "outdoor") {
            return 'fa fa-sun-o';
        } else if (shareType == "sport") {
            return 'fa fa-futbol-o';
        } else if (shareType == "auto") {
            return 'fa fa-car';
        } else if (shareType == "moto") {
            return 'fa fa-motorcycle';
        } else if (shareType == "music") {
            return 'fa fa-music';
        } else if (shareType == "pet") {
            return 'fa fa-paw';
        } else {
            return 'fa fa-paint-brush';
        }
    } else if (shareTypeCategory == "mode") {
        if (shareType == "man") {
            return 'fa fa-male';
        } else if (shareType == "woman") {
            return 'fa fa-female';
        } else if (shareType == "mixte") {
            return 'icon ion-ios-body';
        } else if (shareType == "child") {
            return 'fa fa-child';
        } else if (shareType == "jewelry") {
            return 'fa fa-diamond';
        } else {
            return 'icon ion-tshirt';
        }
    } else if (shareTypeCategory == "house") {
        if (shareType == "furniture") {
            return 'fa fa-archive';
        } else if (shareType == "kitchen") {
            return 'icon ion-knife';
        } else if (shareType == "diy") {
            return 'fa fa-wrench';
        } else {
            return 'fa fa-home';
        }
    } else if (shareTypeCategory == "service") {
        if (shareType == "travel") {
            return 'fa fa-suitcase';
        } else if (shareType == "hotel") {
            return 'fa fa-bed';
        } else if (shareType == "wellness") {
            return 'fa fa-smile-o';
        } else {
            return 'fa fa-briefcase';
        }
    } else if (shareTypeCategory == "other") {
        return 'fa fa-ellipsis-h';
    } else {
        return 'fa fa-question-circle';
    }
}

/**
 * Method used to get a color corresponding to a specific share type category
 * @param shareTypeCategory
 * @returns {string}
 */
function getIconColor(shareTypeCategory) {
    if (shareTypeCategory == "food") {
        return CARROT_COLOR;
    } else if (shareTypeCategory == "hightech") {
        return BELIZE_HOLE_COLOR;
    } else if (shareTypeCategory == "audiovisual") {
        return GREEN_SEA_COLOR;
    } else if (shareTypeCategory == "recreation") {
        return NEPHRITIS_COLOR;
    } else if (shareTypeCategory == "mode") {
        return WISTERIA_COLOR;
    } else if (shareTypeCategory == "house") {
        return POMEGRANATE_COLOR;
    } else if (shareTypeCategory == "service") {
        return WET_ASPHALT_COLOR;
    } else if (shareTypeCategory == "other") {
        return ASBESTOS_COLOR;
    } else {
        return CONCRETE_COLOR;
    }
}

function getTypesWithShareTypeCategory(shareTypeCategory, shareTypeCategories) {
    var types = null;

    if (shareTypeCategory != -1) {
        types = [];

        var shareTypes = shareTypeCategories[shareTypeCategory]['share_types'];

        for (var shareTypeId in shareTypes) {
            types.push(shareTypeId);
        }
    }

    return types;
}

function getTypesWithShareType(shareType, shareTypeCategory, shareTypeCategories) {
    var types = null;

    if (shareType == -1) {
        types = [];

        var shareTypes = shareTypeCategories[shareTypeCategory]['share_types'];

        for (var shareTypeId in shareTypes) {
            types.push(shareTypeId);
        }
    } else {
        types = [shareType];
    }

    return types;
}

function getShareTypeCategories(scope, data) {

    //All category
    scope.shareTypeCategories[-1] = {
        "label": "all",
        "share_type_category_id": -1,
        "share_types": []
    };

    //Loop on results
    for (var shareTypeCategoryIndex in data.results) {
        var shareTypeCategory = data.results[shareTypeCategoryIndex];
        var shareTypes = shareTypeCategory['share_types'];

        //
        shareTypeCategory['share_types'] = {};
        shareTypeCategory['share_types'][-1] = {
            "label": "all",
            "share_type_category_id": shareTypeCategory.share_type_category_id,
            "share_type_id": -1
        };

        //
        for (var shareTypeIndex in shareTypes) {
            var shareType = shareTypes[shareTypeIndex];
            shareTypeCategory['share_types'][shareType.share_type_id] = shareType;
        }

        scope.shareTypeCategories[shareTypeCategory.share_type_category_id] = shareTypeCategory;
    }
}
