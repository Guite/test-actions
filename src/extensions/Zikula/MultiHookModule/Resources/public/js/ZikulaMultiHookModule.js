'use strict';

function zikulaMultiHookCapitaliseFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.substring(1);
}

/**
 * Initialise the quick navigation form in list views.
 */
function zikulaMultiHookInitQuickNavigation() {
    var quickNavForm;
    var objectType;

    if (jQuery('.zikulamultihookmodule-quicknav').length < 1) {
        return;
    }

    quickNavForm = jQuery('.zikulamultihookmodule-quicknav').first();
    objectType = quickNavForm.attr('id').replace('zikulaMultiHookModule', '').replace('QuickNavForm', '');

    var quickNavFilterTimer;
    quickNavForm.find('select').change(function (event) {
        clearTimeout(quickNavFilterTimer);
        quickNavFilterTimer = setTimeout(function() {
            quickNavForm.submit();
        }, 5000);
    });

    var fieldPrefix = 'zikulamultihookmodule_' + objectType.toLowerCase() + 'quicknav_';
    // we can hide the submit button if we have no visible quick search field
    if (jQuery('#' + fieldPrefix + 'q').length < 1 || jQuery('#' + fieldPrefix + 'q').parent().parent().hasClass('d-none')) {
        jQuery('#' + fieldPrefix + 'updateview').addClass('d-none');
    }
}

/**
 * Toggles a certain flag for a given item.
 */
function zikulaMultiHookToggleFlag(objectType, fieldName, itemId) {
    jQuery.ajax({
        method: 'POST',
        url: Routing.generate('zikulamultihookmodule_ajax_toggleflag'),
        data: {
            ot: objectType,
            field: fieldName,
            id: itemId
        }
    }).done(function (data) {
        var idSuffix;
        var toggleLink;

        idSuffix = zikulaMultiHookCapitaliseFirstLetter(fieldName) + itemId;
        toggleLink = jQuery('#toggle' + idSuffix);

        /*if (data.message) {
            zikulaMultiHookSimpleAlert(toggleLink, Translator.trans('Success'), data.message, 'toggle' + idSuffix + 'DoneAlert', 'success');
        }*/

        toggleLink.find('.fa-check').toggleClass('d-none', true !== data.state);
        toggleLink.find('.fa-times').toggleClass('d-none', true === data.state);
    });
}

/**
 * Initialise ajax-based toggle for all affected boolean fields on the current page.
 */
function zikulaMultiHookInitAjaxToggles() {
    jQuery('.zikulamultihook-ajax-toggle').click(function (event) {
        var objectType;
        var fieldName;
        var itemId;

        event.preventDefault();
        objectType = jQuery(this).data('object-type');
        fieldName = jQuery(this).data('field-name');
        itemId = jQuery(this).data('item-id');

        zikulaMultiHookToggleFlag(objectType, fieldName, itemId);
    }).removeClass('d-none');
}

/**
 * Simulates a simple alert using bootstrap.
 */
function zikulaMultiHookSimpleAlert(anchorElement, title, content, alertId, cssClass) {
    var alertBox;

    alertBox = ' \
        <div id="' + alertId + '" class="alert alert-' + cssClass + ' fade show"> \
          <button type="button" class="close" data-dismiss="alert">&times;</button> \
          <h4>' + title + '</h4> \
          <p>' + content + '</p> \
        </div>';

    // insert alert before the given anchor element
    anchorElement.before(alertBox);

    jQuery('#' + alertId).delay(200).addClass('in').fadeOut(4000, function () {
        jQuery(this).remove();
    });
}

/**
 * Initialises the mass toggle functionality for admin view pages.
 */
function zikulaMultiHookInitMassToggle() {
    if (jQuery('.zikulamultihook-mass-toggle').length > 0) {
        jQuery('.zikulamultihook-mass-toggle').unbind('click').click(function (event) {
            jQuery('.zikulamultihook-toggle-checkbox').prop('checked', jQuery(this).prop('checked'));
        });
    }
}

/**
 * Creates a dropdown menu for the item actions.
 */
function zikulaMultiHookInitItemActions(context) {
    var containerSelector;
    var containers;
    
    containerSelector = '';
    if ('view' === context) {
        containerSelector = '.zikulamultihookmodule-view';
    } else if ('display' === context) {
        containerSelector = 'h2, h3';
    }
    
    if ('' === containerSelector) {
        return;
    }
    
    containers = jQuery(containerSelector);
    if (containers.length < 1) {
        return;
    }
    
    containers.find('.dropdown > ul').removeClass('nav').addClass('list-unstyled dropdown-menu');
    containers.find('.dropdown > ul > li').addClass('dropdown-item').css('padding', 0);
    containers.find('.dropdown > ul a').addClass('d-block').css('padding', '3px 5px');
    containers.find('.dropdown > ul a i').addClass('fa-fw mr-1');
    if (containers.find('.dropdown-toggle').length > 0) {
        containers.find('.dropdown-toggle').removeClass('d-none').dropdown();
    }
}

jQuery(document).ready(function () {
    var isViewPage;
    var isDisplayPage;

    isViewPage = jQuery('.zikulamultihookmodule-view').length > 0;
    isDisplayPage = jQuery('.zikulamultihookmodule-display').length > 0;

    if (isViewPage) {
        zikulaMultiHookInitQuickNavigation();
        zikulaMultiHookInitMassToggle();
        zikulaMultiHookInitItemActions('view');
        zikulaMultiHookInitAjaxToggles();
    } else if (isDisplayPage) {
        zikulaMultiHookInitItemActions('display');
        zikulaMultiHookInitAjaxToggles();
    }
});
