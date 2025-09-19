$(function () {
    
    /**
     * lets show the spinner and deactivate button after
     * clicking login to avoid double clicking
     */
    $('#loginForm').on('submit', function () {
        var $btn = $('#loginBtn');
        //var $text = $btn.find('.btn-text');
        var $spinner = $btn.find('.fa-spinner');
    
        // Show spinner and disable the button
        //$text.hide();
        $spinner.show();
        $btn.prop('disabled', true);
    });


    /**
     * lets show the spinner and deactivate button after
     * clicking email reset to avoid double clicking
     */
    $('#emailresetForm').on('submit', function () {
        var $btn = $('#emailresetBtn');
        //var $text = $btn.find('.btn-text');
        var $spinner = $btn.find('.fa-spinner');
    
        // Show spinner and disable the button
        //$text.hide();
        $spinner.show();
        $btn.prop('disabled', true);
    });


    /**
     * lets show the spinner and deactivate button after
     * clicking register to avoid double clicking
     */
    $('#registerForm').on('submit', function () {
        var $btn = $('#registerBtn');
        //var $text = $btn.find('.btn-text');
        var $spinner = $btn.find('.fa-spinner');
    
        // Show spinner and disable the button
        //$text.hide();
        $spinner.show();
        $btn.prop('disabled', true);
    });

    /**
     * lets show the spinner and deactivate button after
     * clicking verification to avoid double clicking
     */
    $('#verificationForm').on('submit', function () {
        var $btn = $('#verificationBtn');
        //var $text = $btn.find('.btn-text');
        var $spinner = $btn.find('.fa-spinner');
    
        // Show spinner and disable the button
        //$text.hide();
        $spinner.show();
        $btn.prop('disabled', true);
    });

    /**
     * lets show the spinner and deactivate button after
     * clicking password reset to avoid double clicking
     */
    $('#passwordresetForm').on('submit', function () {
        var $btn = $('#passwordresetBtn');
        //var $text = $btn.find('.btn-text');
        var $spinner = $btn.find('.fa-spinner');
    
        // Show spinner and disable the button
        //$text.hide();
        $spinner.show();
        $btn.prop('disabled', true);
    });


     /**
     * lets show the spinner and deactivate button after
     * clicking timezone  to avoid double clicking
     */
    $('#timezoneForm').on('submit', function () {
        var $btn = $('#timezoneBtn');
        //var $text = $btn.find('.btn-text');
        var $spinner = $btn.find('.fa-spinner');
    
        // Show spinner and disable the button
        //$text.hide();
        $spinner.show();
        $btn.prop('disabled', true);
    });


      /**
     * lets show the spinner and deactivate button after
     * clicking change password to avoid double clicking
     */
    $('#changepasswordForm').on('submit', function () {
        var $btn = $('#changepasswordBtn');
        //var $text = $btn.find('.btn-text');
        var $spinner = $btn.find('.fa-spinner');
    
        // Show spinner and disable the button
        //$text.hide();
        $spinner.show();
        $btn.prop('disabled', true);
    });

    
    
    /**
     * lets show the spinner and deactivate button after
     * clicking unlock to avoid double clicking
     */
    $('#unlockForm').on('submit', function () {
        var $btn = $('#unlockBtn');
        //var $text = $btn.find('.btn-text');
        var $spinner = $btn.find('.fa-spinner');
    
        // Show spinner and disable the button
        //$text.hide();
        $spinner.show();
        $btn.prop('disabled', true);
    });


  
});