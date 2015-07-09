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
            return 'icon-ion-pizza';
        } else if (shareType == "snack") {
            return 'icon-coffee';
        } else if (shareType == "burger") {
            return 'icon-fast-food';
        } else if (shareType == "sushi") {
            return 'icon-food-1';
        } else if (shareType == "restaurant") {
            return 'icon-glass';
        } else {
            return 'icon-food';
        }
    } else if (shareTypeCategory == "hightech") {
        if (shareType == "component") {
            return 'icon-keyboard';
        } else if (shareType == "computer") {
            return 'icon-desktop';
        } else if (shareType == "phone") {
            return 'icon-mobile';
        } else if (shareType == "storage") {
            return 'icon-hdd';
        } else if (shareType == "application") {
            return 'icon-tablet';
        } else {
            return 'icon-laptop';
        }
    } else if (shareTypeCategory == "audiovisual") {
        if (shareType == "picture") {
            return 'icon-picture';
        } else if (shareType == "sound") {
            return 'icon-volume-up';
        } else if (shareType == "photo") {
            return 'icon-camera-alt';
        } else if (shareType == "disc") {
            return 'icon-cd';
        } else if (shareType == "game") {
            return 'icon-gamepad';
        } else {
            return 'icon-headphones';
        }
    } else if (shareTypeCategory == "recreation") {
        if (shareType == "cinema") {
            return 'icon-video';
        } else if (shareType == "show") {
            return 'icon-theatre';
        } else if (shareType == "book") {
            return 'icon-book';
        } else if (shareType == "outdoor") {
            return 'icon-sun';
        } else if (shareType == "sport") {
            return 'icon-soccer-ball';
        } else if (shareType == "auto") {
            return 'icon-cab';
        } else if (shareType == "moto") {
            return 'icon-motorcycle';
        } else if (shareType == "music") {
            return 'icon-music';
        } else if (shareType == "pet") {
            return 'icon-paw';
        } else if (shareType == "game") {
            return 'icon-puzzle';
        } else {
            return 'icon-brush';
        }
    } else if (shareTypeCategory == "mode") {
        if (shareType == "man") {
            return 'icon-male';
        } else if (shareType == "woman") {
            return 'icon-female';
        } else if (shareType == "mixte") {
            return 'icon-transgender';
        } else if (shareType == "child") {
            return 'icon-child';
        } else if (shareType == "jewelry") {
            return 'icon-diamond';
        } else {
            return 'icon-basket';
        }
    } else if (shareTypeCategory == "house") {
        if (shareType == "furniture") {
            return 'icon-box';
        } else if (shareType == "kitchen") {
            return 'icon-ion-knife';
        } else if (shareType == "diy") {
            return 'icon-wrench';
        } else {
            return 'icon-home';
        }
    } else if (shareTypeCategory == "service") {
        if (shareType == "travel") {
            return 'icon-suitcase';
        } else if (shareType == "hotel") {
            return 'icon-bed';
        } else if (shareType == "wellness") {
            return 'icon-smile';
        } else {
            return 'icon-briefcase';
        }
    } else if (shareTypeCategory == "other") {
        return 'icon-ellipsis';
    } else {
        return 'icon-question-circle';
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

    if (shareTypeCategory != "-1") {
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

    if (shareType == "-1") {
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

function getShareTypes(scope, data) {
    //Loop on results
    for (var shareTypeCategoryIndex in data.results) {
        var shareTypeCategory = data.results[shareTypeCategoryIndex];
        var shareTypes = shareTypeCategory['share_types'];
        //
        for (var shareTypeIndex in shareTypes) {
            var shareType = shareTypes[shareTypeIndex];
            shareType['share_type_category_label'] = shareTypeCategory.label;

            scope.shareTypes[shareType.share_type_id] = shareType;
        }
    }
}

function getShareTypeCategories(scope, data) {
    //All category
    scope.shareTypeCategories['-1'] = {
        "label": "all",
        "share_type_category_id": "-1",
        "share_types": []
    };

    //Loop on results
    for (var shareTypeCategoryIndex in data.results) {
        var shareTypeCategory = data.results[shareTypeCategoryIndex];
        var shareTypes = shareTypeCategory['share_types'];

        //
        shareTypeCategory['share_types'] = {};
        shareTypeCategory['share_types']['-1'] = {
            "label": "all",
            "share_type_category_id": shareTypeCategory.share_type_category_id,
            "share_type_id": "-1"
        };

        //
        for (var shareTypeIndex in shareTypes) {
            var shareType = shareTypes[shareTypeIndex];
            shareTypeCategory['share_types'][shareType.share_type_id] = shareType;
        }

        scope.shareTypeCategories[shareTypeCategory.share_type_category_id] = shareTypeCategory;
    }
}

function getShareTypeLabel(shareTypeCategory, shareType) {
    if (shareTypeCategory == "food") {
        if (shareType == "pizza") {
            return 'Pizza';
        } else if (shareType == "snack") {
            return 'Snack';
        } else if (shareType == "burger") {
            return 'Burger';
        } else if (shareType == "sushi") {
            return 'Sushi';
        } else if (shareType == "restaurant") {
            return 'Restaurant';
        } else if (shareType == "all") {
            return 'Type';
        } else {
            return 'Autre';
        }
    } else if (shareTypeCategory == "hightech") {
        if (shareType == "component") {
            return 'Composants';
        } else if (shareType == "computer") {
            return 'Ordinateur';
        } else if (shareType == "phone") {
            return 'Téléphonie';
        } else if (shareType == "storage") {
            return 'Stockage';
        } else if (shareType == "application") {
            return 'Application';
        } else if (shareType == "all") {
            return 'Type';
        } else {
            return 'Autre';
        }
    } else if (shareTypeCategory == "audiovisual") {
        if (shareType == "picture") {
            return 'Images';
        } else if (shareType == "sound") {
            return 'Audio';
        } else if (shareType == "photo") {
            return 'Photo';
        } else if (shareType == "disc") {
            return 'CD/DVD';
        } else if (shareType == "game") {
            return 'Jeux';
        } else if (shareType == "all") {
            return 'Type';
        } else {
            return 'Autre';
        }
    } else if (shareTypeCategory == "recreation") {
        if (shareType == "cinema") {
            return 'Cinéma';
        } else if (shareType == "show") {
            return 'Spectacles';
        } else if (shareType == "game") {
            return 'Jeux';
        } else if (shareType == "book") {
            return 'Livres';
        } else if (shareType == "outdoor") {
            return 'Extérieur';
        } else if (shareType == "sport") {
            return 'Sports';
        } else if (shareType == "auto") {
            return 'Auto';
        } else if (shareType == "moto") {
            return 'Moto';
        } else if (shareType == "music") {
            return 'Musique';
        } else if (shareType == "pet") {
            return 'Animaux';
        } else if (shareType == "all") {
            return 'Type';
        } else {
            return 'Autre';
        }
    } else if (shareTypeCategory == "mode") {
        if (shareType == "man") {
            return 'Homme';
        } else if (shareType == "woman") {
            return 'Femme';
        } else if (shareType == "mixte") {
            return 'Mixte';
        } else if (shareType == "child") {
            return 'Enfant';
        } else if (shareType == "jewelry") {
            return 'Bijoux';
        } else if (shareType == "all") {
            return 'Type';
        } else {
            return 'Autre';
        }
    } else if (shareTypeCategory == "house") {
        if (shareType == "furniture") {
            return 'Meubles';
        } else if (shareType == "kitchen") {
            return 'Cuisine';
        } else if (shareType == "diy") {
            return 'Bricolage';
        } else if (shareType == "all") {
            return 'Type';
        } else {
            return 'Autre';
        }
    } else if (shareTypeCategory == "service") {
        if (shareType == "travel") {
            return 'Voyage';
        } else if (shareType == "hotel") {
            return 'Hôtel';
        } else if (shareType == "wellness") {
            return 'Bien-être';
        } else if (shareType == "all") {
            return 'Type';
        } else {
            return 'Autre';
        }
    } else if (shareTypeCategory == "other") {
        if (shareType == "all") {
            return 'Type';
        } else {
            return 'Autre';
        }
    } else {
        return 'Type';
    }
}

function getShareTypeCategoryLabel(shareTypeCategory) {
    if (shareTypeCategory == "food") {
        return 'Restauration';
    } else if (shareTypeCategory == "hightech") {
        return 'High-Tech';
    } else if (shareTypeCategory == "audiovisual") {
        return 'Audiovisuel';
    } else if (shareTypeCategory == "recreation") {
        return 'Loisirs';
    } else if (shareTypeCategory == "mode") {
        return 'Mode';
    } else if (shareTypeCategory == "house") {
        return 'Maison';
    } else if (shareTypeCategory == "service") {
        return 'Services';
    } else if (shareTypeCategory == "other") {
        return 'Autre';
    } else if (shareTypeCategory == "all") {
        return 'Catégorie';
    } else {
        return null;
    }
}

function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}

function geolocate(scope, onLocationReceived) {
    //
    if (navigator.geolocation) {
        //Ask the user position
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });

            //$scope.autocomplete.setBounds(circle.getBounds());

            //Center map
            scope.map.fitBounds(circle.getBounds());

            //Call delegate
            if (typeof onLocationReceived === 'function') {
                onLocationReceived(position);
            }
        });
    }
}

function getStartBounds() {
    return new google.maps.LatLngBounds(
        new google.maps.LatLng(48.815573, 2.2241989999999987),
        new google.maps.LatLng(48.9021449, 2.4699207999999544)
    );
}

function centerMapOnPlace(scope, place) {
    //If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
        scope.map.fitBounds(place.geometry.viewport);
    } else {
        scope.map.setCenter(place.geometry.location);
        scope.map.setZoom(17);  // Why 17? Because it looks good.
    }
}

function showShareDetails(shareId) {
    //Simply change window location
    window.location.href = webroot + "share/details/" + shareId;
}