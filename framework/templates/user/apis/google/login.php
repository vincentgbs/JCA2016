<meta name="google-signin-scope" content="profile email">
<meta name="google-signin-client_id" content="<?php echo (isset($data['client_id'])?$data['client_id']:''); ?>">
<script src="https://apis.google.com/js/platform.js" async defer></script>

<style>
.close-icon {display:block;box-sizing:border-box;width:20px;height:20px;border-width:3px;border-style: solid;border-color:grey;border-radius:100%;
background: -webkit-linear-gradient(-45deg, transparent 0%, transparent 46%, white 46%,  white 56%,transparent 56%, transparent 100%), -webkit-linear-gradient(45deg, transparent 0%, transparent 46%, white 46%,  white 56%,transparent 56%, transparent 100%);
background-color:grey;box-shadow:0px 0px 5px 2px rgba(0,0,0,0.5);}
</style>

<div class="float_right" id="close_lightbox"> <span class="close-icon"></span> </div>

<div class="center" style="margin-top:10px;">
    <div class="row col-md-12 text-center" id="message"></div>
    <div class="row col-md-12">
        <div class="col-md-6">
            <div class="g-signin2" data-onsuccess="onSignIn" data-theme="light"></div>
        </div>
        <div class="col-md-6">
            <div id="signOut">
                <div style="height:36px;width:120px;" class="abcRioButton abcRioButtonLightBlue">
                    <div class="abcRioButtonContentWrapper">
                        <div class="abcRioButtonIcon" style="padding:8px">
                            <div style="width:18px;height:18px;" class="abcRioButtonSvgImageWithFallback abcRioButtonIconImage abcRioButtonIconImage18">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg">
                                    <g>
                                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                                        <path fill="none" d="M0 0h48v48H0z"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <span style="font-size:13px;line-height:34px;" class="abcRioButtonContents">
                            <span>Sign Out</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
