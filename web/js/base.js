/*!
 * Base JavaScript Document
 *
 * copyright 2015, Yury Istomenok <iyl@tut.by>
 */


/**
 * Namespace for project
 *
 * @type {Object}
 */
var Project = {};


/**
 * Namespace for Core bundle
 *
 * @type {Object}
 */
Project.Base = {};


/**
 * Object for loading indicator (class)
 *
 * @type {Function}
 */
Project.Base.Waiting = (function() {

    var nameClassIndicator = 'waiting-indicator';
    var indicatorHtml = '<div class="' + nameClassIndicator + '"></div>';

    /**
     * Constructor
     */
    var Obj = function() {};

    /**
     * @param wrapDiv wrapper for indicator
     * @return {*}
     */
    Obj = {
        /**
         * @param wrapDiv
         * @return {*}
         */
        show: function(wrapDiv) {
            if (!wrapDiv) {
                return;
            }

            var content = wrapDiv.html();
            this.indicator =  $(indicatorHtml);

            wrapDiv.html('<div class="waiting-container">' + indicatorHtml + content + '</div>');

            return this;
        },

        /**
         * Removing of a indicator
         * @return {*}
         */
        hide: function() {
            $('.' + nameClassIndicator).remove();

            return this;
        }
    };

    return Obj;
});


Project.Base.WaitLoadFile = (function(){

    var nameClassIndicator = 'wait-load-file-indicator';
    var indicatorHtml = '<div class="' + nameClassIndicator + '"></div>';

    /**
     * Constructor
     */
    var Obj = function() {};

    /**
     * @param wrapDiv wrapper for indicator
     * @return {*}
     */
    Obj = {
        /**
         * @param wrapDiv
         * @return {*}
         */
        show: function(wrapDiv) {
            if (!wrapDiv) {
                return;
            }
            wrapDiv.html(indicatorHtml);

            return this;
        },

        /**
         * Removing of a indicator
         * @return {*}
         */
        hide: function() {
            $('.' + nameClassIndicator).remove();

            return this;
        }
    };

    return Obj;

});


Project.Base.LoadForm = (function(obj, path, type, id) {

    var link = $(obj);
    var formContainerId = type + '_form_container_' + id;
    var formContainer = $('#' + formContainerId);
    var formId = type + '_form_' + id;

    formContainer.show();

    var waiting = new Project.Base.Waiting();
    waiting.show(formContainer);

    $.ajax({
        type: "POST",
        url: path,
        dataType: "json",
        success: function(data){
            if (data.success) {
                formContainer.html(data.result);
                link.hide();
                var form = $('#' + formId);

                if (type == 'edit_user_institution') {
                    Institutions.initAutocompleter(formId);
                }

                if (type = 'edit_user_job_company') {
                    JobCompanies.addAutocomplete('edit_user_job_company_form_' + id);
                    new Project.User.Profile.EditTree({block: $('.profileJobCompanyIndustriesInput', form)});
                    attachePlainInputInformer($('.jsPlainInputWithInformer', form));
                }
            }
            waiting.hide();

        }
    });

    return false;

});



/**
 * @type {Project.Base.PopupContent}
 */
Project.Base.PopupContent = (function(item) {

    var $link = $(item);
    var $content = $('.popup_wrap_content');

    /**
     * Constructor
     */
    var Obj = function() {};

    /**
     * @type {Object}
     */
    Obj = {

        /**
         * @return {*}
         */
        show: function() {
            var wait = new Project.Base.Waiting();
            wait.show($content);
            this.ajaxResponse();

            return this;
        },

        /**
         * @return {*}
         */
        ajaxResponse: function() {

            var url = $link.attr('href');

            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                success: function(data){
                    $content.html(data.template);
                }
            });

            return this;
        }
    };

    return Obj;
});
