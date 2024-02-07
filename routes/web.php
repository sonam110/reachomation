<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ScheduleCampaignController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\RevealedController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\OutlookMailController;
use App\Http\Controllers\AddEmailController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\MailTrackController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\RefreshTokenController;

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
 //   Artisan::call('optimize');
 
    return "Cleared!";
 
 });

 Route::get('/payment', function () {
        return view('payment1');
    })->name('payment');

Route::get('/downloadfile',[ScheduleCampaignController::class, 'downloadfile']);
Route::get('/refreshtoken',[RefreshTokenController::class, 'refreshtoken']);


Route::get('/pricing', [FrontController::class, 'pricing'])->name('pricing');
Route::get('/payment-billing', [FrontController::class, 'paymentBilling'])->name('payment-billing');
Route::post('/fetch-plan', [FrontController::class, 'fatchPlan'])->name('fetch-plan');
Route::get('/to-many-account/{email}', [FrontController::class, 'toManyAccount'])->name('to-many-account');
Route::post('/pause-session', [FrontController::class, 'pauseSession'])->name('pause.session');
Route::post('/save-contact', [FrontController::class, 'saveContact'])->name('save.contact');
  
Route::post('/stripe-webhook', [WebhookController::class, 'stripeWebhook'])->name('stripe-webhook');
/*---------Mail Tracking---------------*/
Route::get('/track-mail/{token}', [MailTrackController::class, 'mailTrack'])->name('track-mail');
Route::get('/click-mail/{token}', [MailTrackController::class, 'clickMail'])->name('click-mail');
Route::get('/forgot-password',  [ForgotPasswordController::class, 'forgetPassword'])->name('forgot-password');
Route::post('/forgotpassword',  [ForgotPasswordController::class, 'fPassword'])->name('forgotpassword');
Route::get('/password-reset/{token}',  [ForgotPasswordController::class, 'passwordRes'])->name('password-reset/{token}');
Route::post('/passwordReset',  [ForgotPasswordController::class, 'passwordReset'])->name('passwordReset');
Route::get('/email-verification/{id}/{hash}',  [UserController::class, 'emailVerification'])->name('email-verification');
Route::get('/unsubscribe/{token}', [ScheduleCampaignController::class, 'unsubscribeMail'])->name('unsubscribe-mail');


Route::middleware(['auth'])->group(function (){
    Route::middleware(['is-subscription-expire'])->group(function (){

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/credit-ledger', [DashboardController::class, 'transactionHistory'])->name('transaction-history');

        // lists controllers
        Route::get('/lists', [ListController::class, 'index'])->name('lists');
        Route::post('/createList', [ListController::class, 'createList']);
        Route::post('/editList', [ListController::class, 'editList']);
        Route::post('/addtoList', [ListController::class, 'addtoList']);
        Route::post('/removetoList', [ListController::class, 'removetoList']);
        Route::post('/deleteList', [ListController::class, 'deleteList']);
        Route::get('/download-unsubscribed-list', [ListController::class, 'downloadUnsubscribedList'])->name('download-unsubscribed-list');

        // favourites controllers
        Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites');
        Route::post('/addtoFavourites', [UserController::class, 'addtoFavourites']);
        Route::post('/removetoFavourites', [UserController::class, 'removetoFavourites']);

        // user controllers
        Route::post('/hide', [UserController::class, 'hide']);
        Route::post('/unhide', [UserController::class, 'unhide']);
        Route::post('/revealedDomain', [UserController::class, 'revealedDomain']);
        Route::post('/openTraffic', [UserController::class, 'openTraffic']);
        Route::post('/getEmail', [UserController::class, 'getEmail']);
        Route::post('/insertTemplate', [UserController::class, 'insertTemplate']);
        Route::post('/set-default-templete', [UserController::class, 'setDefaultTemplate'])->name('set-default-templete');
        Route::post('/testMail', [UserController::class, 'testmail']);
        Route::post('/sendMail', [UserController::class, 'testmail']);
        Route::post('/updateNiches', [UserController::class, 'updateNiches']);
        Route::post('/updateUserInterest', [UserController::class, 'updateUserInterest']);

        // template controllers
        Route::get('/templates', [TemplateController::class, 'index'])->name('templates');
        Route::post('/createTemplate', [TemplateController::class, 'createTemplate']);
        Route::post('/editTemplate', [TemplateController::class, 'editTemplate']);
        Route::post('/testmail', [TemplateController::class, 'testmail']);
        Route::post('/editTemplate', [TemplateController::class, 'editTemplate']);
        Route::post('/editedTemplate', [TemplateController::class, 'editedTemplate']);
        Route::post('/copyTemplate', [TemplateController::class, 'copyTemplate']);
        Route::post('/deleteTemplate', [TemplateController::class, 'deleteTemplate']);
        Route::post('/templateGroups', [TemplateController::class, 'templateGroups']);
        Route::post('/setDefault', [TemplateController::class, 'setDefault']);
        Route::post('/template-mail-preview', [TemplateController::class, 'templateMailPreview'])->name('template-mail-preview');

        // schedule campaign controllers
        Route::get('/schedulecampaign/{id?}', [ScheduleCampaignController::class, 'index'])->name('schedulecampaign');

        Route::post('/get-email-account', [ScheduleCampaignController::class, 'getEmailAccount'])->name('get-email-account');
        Route::post('/loadList', [ScheduleCampaignController::class, 'loadList']);
        Route::post('/list-domain-collection', [ScheduleCampaignController::class, 'listDomainCollection'])->name('list-domain-collection');
        Route::post('/list-campaign-data', [ScheduleCampaignController::class, 'listCampaignCollection'])->name('list-campaign-data');

        Route::post('/createCampaign', [ScheduleCampaignController::class, 'createCampaign'])->name('create.campaign');
        Route::post('/editCampaign', [ScheduleCampaignController::class, 'editCampaign'])->name('edit.campaign');
        Route::post('/get-custom-data', [ScheduleCampaignController::class,'getCustomData'])->name('get-custom-data');
        
        Route::post('/domain-upload', [ScheduleCampaignController::class,'domainUpload'])->name('domain-upload');
        Route::post('/preview-campaign', [ScheduleCampaignController::class,'PreviewCampaign'])->name('preview-campaign');

        Route::post('/credit-model', [ScheduleCampaignController::class,'creditModel'])->name('credit-model');
        Route::post('/edit-credit-model', [ScheduleCampaignController::class,'editCreditModel'])->name('edit-credit-model');
       

        
        
        // campaign controllers
        Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns');
        Route::post('/campaign-list', [CampaignController::class, 'campaignList'])->name('api.campaign-list');
        Route::get('/edit-campaign/{uuid}', [CampaignController::class, 'editCampaign'])->name('edit-campaign');
        Route::get('/campaign-details/{uuid}', [CampaignController::class, 'campaignDetails'])->name('campaign-details');
        Route::post('/preview-message', [CampaignController::class, 'previewMessage'])->name('preview-message');
        Route::post('/preview-message-inbox', [CampaignController::class, 'previewMessageInbox'])->name('preview-message-inbox');
        
        Route::post('/show-statics', [CampaignController::class, 'showStatics'])->name('show-statics');
        Route::get('/download-report/{id}/{level?}', [CampaignController::class, 'downloadReport'])->name('download-report');
        Route::post('/opy-campaign', [ScheduleCampaignController::class, 'copyCampaign'])->name('copy-campaign');
        Route::post('/delete-campaign', [ScheduleCampaignController::class, 'deleteCampaign'])->name('delete-campaign');
        Route::post('/schedule-campaign', [ScheduleCampaignController::class, 'scheduleCampaign'])->name('schedule-campaign');
        Route::post('/play-pause-campaign', [CampaignController::class, 'playPauseCampaign'])->name('play-pause-campaign');

        Route::post('/get-send-mail', [CampaignController::class, 'getSendMail'])->name('api.get-send-mail');




        /*-------Send Test Campaign Mail*/
        Route::post('/send-mail', [ScheduleCampaignController::class, 'sendMail'])->name('send-mail');
        Route::post('/send-preview-mail', [ScheduleCampaignController::class, 'sendPreviewMail'])->name('send-preview-mail');
       

       
        // revealed controllers
        Route::get('/revealed', [RevealedController::class, 'index'])->name('revealed');
        Route::post('/get-domain-cost', [RevealedController::class, 'getDomainCost'])->name('get-domain-cost');

        /*------Inbox/feed------------------*/
        Route::get('/feeds', [InboxController::class, 'feeds'])->name('feeds');
        Route::post('/feeds-list', [InboxController::class, 'feedsList'])->name('api.feeds-list');
        Route::get('/sendbox', [InboxController::class, 'sendbox'])->name('sendbox');
        Route::post('/sendbox-list', [InboxController::class, 'sendBoxList'])->name('api.sendbox-list');
       
        
        
        Route::get('/inbox', function () {
            return view('inbox');
        })->name('inbox');
        
       

        Route::get('/read-notification/{id}',[ NotificationController::class,'readNotification'])->name('read-notification');
        Route::get('/read-all-notification',[ NotificationController::class,'readAllNotification'])->name('read-all-notification');
        Route::post('/click-read-all-notification',[ NotificationController::class,'clickReadAllNotification'])->name('click-read-all-notification');
        Route::get('/notification-list',[ NotificationController::class,'notificationList'])->name('notification-list');
       
         Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');
         Route::post('/update-profile', [SettingsController::class, 'updateProfile'])->name('update-profile');
         Route::post('/user-save-password', [SettingsController::class, 'userSavePassword'])->name('user-save-password');

         Route::post('/get-state', [SettingsController::class, 'getState'])->name('get-state');

        // Route::get('/favourites', function () {
        //     return view('favourites');
        // })->name('favourites');

        // Route::get('/hidden', function () {
        //     return view('hidden');
        // })->name('hidden');

        Route::get('/search', [SearchController::class, 'index'])->name('search');
        Route::post('/get-domain-list', [SearchController::class,'getDomainList'])->name('get-domain-list');
        Route::post('/addDomaintolist', [SearchController::class, 'addDomaintolist'])->name('addDomaintolist');
        Route::post('/add-sites-tolist', [SearchController::class, 'adSitesTolist'])->name('add-sites-tolist');
        //Route::view('pricing', 'front-pages.pricing')->name('pricing');

        Route::get('/billing', [BillingController::class, 'index'])->name('billing');
        Route::get('/download-invoice/{id}', [BillingController::class, 'downloadInvoice'])->name('download-invoice');
        Route::get('/view-subscription/{id}', [BillingController::class, 'viewSubscription'])->name('view-subscription');

        Route::post('/save-card', [BillingController::class, 'store'])->name('save-card');
        Route::post('/card-delete', [BillingController::class, 'destroy'])->name('card-delete');
        Route::post('/set-default-card', [BillingController::class, 'default'])->name('set-default-card');


        Route::post('/add-card-model', [BillingController::class, 'addCardModel'])->name('add-card-model');
        
    });



   
   
    Route::get('/change-plan', [FrontController::class, 'upgradePlan'])->name('upgrade-plan');
    Route::post('/fetch-plans', [FrontController::class, 'fatchPlans'])->name('fetch-plans');

    /*---------------stripe checkout -------------------------*/

    Route::get('/user-checkout/{slug}', [CheckoutController::class, 'userCheckout'])->name('user-checkout');
    Route::get('/upgrade-user-plan', [CheckoutController::class, 'upgradeUserPlan'])->name('upgrade-user-plan');
   
    Route::post('/subscribe', [SubscriptionsController::class, 'subscribe'])->name('subscribe');
    Route::get('/subscription/{slug}', [SubscriptionsController::class, 'subscription'])->name('user-subscription');
    Route::get('/subscription-callback', [SubscriptionsController::class, 'subscriptionCallback'])->name('subscription-callback');

    Route::post('/make-payment', [SubscriptionsController::class, 'makePayment'])->name('make-payment');
    Route::post('/subscription-create', [SubscriptionsController::class, 'subscriptionCreate'])->name('subscription.create');

    Route::post('/purchase-credit-model', [SubscriptionsController::class, 'purchaseCreditModel'])->name('purchase-credit-model');
    Route::post('/purchase.credit', [SubscriptionsController::class, 'purchaseCredit'])->name('purchase.credit');
    Route::get('/cancel-subscription/{id?}', [SubscriptionsController::class, 'cancelSubscription'])->name('cancel-subscription');
     

    Route::view('/topup', 'topup')->name('topup');

    /*Integrate Mail functionality */


    Route::post('/redirect-to-account', [AddEmailController::class, 'rerirectToAccount'])->name('redirect-to-account');

    Route::get('/callback_gmail', [AddEmailController::class, 'callbackGmail'])->name('callback_gmail');  
    Route::get('/callback_outlook', [AddEmailController::class, 'callback_outlook'])->name('callback_outlook');  
    Route::post('/edit-mail-acount', [AddEmailController::class, 'editMailAccount'])->name('edit-mail-acount');  
    Route::post('/account-delete', [AddEmailController::class, 'accountdelete'])->name('account-delete');  
   /* Route::get('/oauth/gmail/callback', function (){
        LaravelGmail::makeToken();
        return redirect()->to('/');
    });*/
   /* Route::get('/oauth/gmail/callback', [GmailController::class, 'gmailCallBack'])->name('gmail-callback');
    Route::get('/gmailuser', [GmailController::class, 'index'])->name('gmailuser');
     Route::get('/oauth/gmail', function (){
        return LaravelGmail::redirect();
    });*/

    // Gmail Start //

    Route::get('/mail', [MailController::class, 'index'])->name('mail');
    Route::get('/gmail_callback', [MailController::class, 'gmail_callback'])->name('gmail_callback');
    Route::get('/gmail_list', [MailController::class, 'gmail_listData'])->name('gmail_list');
    Route::get('/send_gmail', [MailController::class, 'sendMail'])->name('send_gmail');
    Route::post('/mail_gmail_send', [MailController::class, 'mailProcess'])->name('mail_gmail_send');
    Route::post('/gdriveupload', [MailController::class, 'gdriveUpload'])->name('gdriveupload');
    Route::get('/mail_get/{id}/{auth_id}', [MailController::class, 'mailGetList'])->name('mail_get');
    Route::post('/get_gmail_data', [MailController::class, 'listing_data'])->name('get_gmail_data');
    Route::get('/view_gmail/{id}', [MailController::class, 'viewGmailDetail'])->name('view_gmail');
    Route::post('/delete_gmail', [MailController::class, 'deleteGmail'])->name('delete_gmail');
    Route::get('/force_download/{id1}/{id2}', [MailController::class, 'forceDownload'])->name('force_download');

    // Gmail End //


    //Outlook Start //

    Route::get('/outlook_mail', [OutlookMailController::class, 'index'])->name('outlook_mail');
    Route::get('/outlook_callback', [OutlookMailController::class, 'outlook_callback'])->name('outlook_callback');
    Route::get('/outlook_list', [OutlookMailController::class, 'outlook_listData'])->name('outlook_list');
    Route::get('/outlook_mail_get/{id}/{authid}', [OutlookMailController::class, 'mailGetList'])->name('outlook_mail_get');
    Route::post('/get_outlook_data', [OutlookMailController::class, 'listing_data'])->name('get_outlook_data');
    Route::get('/send_outlook_mail', [OutlookMailController::class, 'sendMail'])->name('send_outlook_mail');
    Route::post('/mail_outlook_send', [OutlookMailController::class, 'mailProcess'])->name('mail_outlook_send');
    Route::post('/onedriveupload', [OutlookMailController::class, 'onedriveUpload'])->name('onedriveupload');
    Route::get('/view_outlook_mail/{id}', [OutlookMailController::class, 'viewOutlookDetail'])->name('view_outlook_mail');
    Route::get('/force_download_outlook/{id1}', [OutlookMailController::class, 'forceDownloadOulook'])->name('force_download_outlook');
    Route::post('/delete_outlook_mail', [OutlookMailController::class, 'deleteOutlookMail'])->name('delete_outlook_mail');

    //Outlook End //  


});
Route::middleware(['NonLoginUser'])->group(function (){

    Route::get('/', [FrontController::class, 'index'])->name('index');
    // Route::get('websites', [SearchController::class, 'domainSearch'])->name('websites');

    Route::view('/reviews', 'front-pages.reviews')->name('reviews');

    Route::view('/contact', 'front-pages.contact')->name('contact');
    
    Route::view('/about', 'front-pages.about')->name('about');
   
    Route::view('/guest-post-services', 'front-pages.guest-post-services')->name('guest-post-services');
    Route::view('/blogger-outreach-services', 'front-pages.blogger-outreach-services')->name('blogger-outreach-services');
    Route::view('/link-building-services', 'front-pages.link-building-services')->name('link-building-services');
    Route::view('/contextual-link-building-services', 'front-pages.contextual-link-building-services')->name('contextual-link-building-services');
    Route::view('/outsourcing-link-building', 'front-pages.outsourcing-link-building')->name('outsourcing-link-building');


});
Route::get('/subscription', [SubscriptionsController::class, 'index'])->name('subscription');

Route::get('/google', [GoogleController::class, 'redirecttogoogle'])->name('google');

Route::get('/google/callback', [GoogleController::class, 'handlecallback'])->middleware('guest');

Route::get('/logout', [GoogleController::class, 'logout'])->name('logout.google');

Route::view('/unsubscribe-new', 'unsubscribe-new')->name('unsubscribe-new');

Route::view('/ai-blog-finder', 'ai-blog-finder')->name('ai-blog-finder');

Route::view('/email-extractor', 'email-extractor')->name('email-extractor');

Route::view('/author-finder', 'author-finder')->name('author-finder');

Route::view('/email-validator', 'email-validator')->name('email-validator');

Route::view('/da-semrush-finder', 'da-semrush-finder')->name('da-semrush-finder');

Route::view('/pricing-new', 'front-pages.pricing-new')->name('pricing-new');

Route::view('/grab-free-credits', 'grab-free-credits')->name('grab-free-credits');

Route::view('/terms-of-use', 'front-pages.terms-of-use')->name('terms-of-use');
Route::view('/privacy-policy', 'front-pages.privacy-policy')->name('privacy-policy');
Route::view('/refund-policy', 'front-pages.refund-policy')->name('refund-policy');
Route::view('/cookie-policy', 'front-pages.cookie-policy')->name('cookie-policy');

require __DIR__.'/auth.php';

