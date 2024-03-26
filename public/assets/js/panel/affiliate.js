( () => {
	"use strict";

	const copyBtn = document.querySelector( '.copy-aff-link' );
	copyBtn?.addEventListener( 'click', ev => {
		const codeInput = document.querySelector( '#ref-code' );
		navigator.clipboard.writeText( codeInput.value );
		toastr.success( 'Affiliate link coppied to clipboard.' );
	} )
} )();


function sendRequestForm() {
	"use strict";

	document.getElementById("send_request_button" ).disabled = true;
	document.getElementById("send_request_button" ).innerHTML = magicai_localize.please_wait;


	var formData = new FormData();
	formData.append( 'affiliate_bank_account', $( "#affiliate_bank_account" ).val() );
	formData.append( 'amount', $( "#amount" ).val() );

	$.ajax( {
		type: "post",
		url: "/dashboard/user/affiliates/send-request",
		data: formData,
		contentType: false,
		processData: false,
		success: function ( data ) {
			toastr.success(magicai_localize.requestsent);
			setTimeout( function () {
				location.reload();
			}, 900 );
		},
		error: function ( data ) {
			toastr.error(magicai_localize.cannotwithdraw)
			document.getElementById( "send_request_button" ).disabled = false;
			document.getElementById( "send_request_button" ).innerHTML = magicai_localize.send;
		}
	} );
	return false;
};

function sendInvitationForm() {
    "use strict";

    document.getElementById( "send_invitation_button" ).disabled = true;
    document.getElementById( "send_invitation_button" ).innerHTML = magicai_localize.please_wait;


    var formData = new FormData();
    formData.append( 'to_mail', $( "#to_mail" ).val() );

    $.ajax( {
        type: "post",
        url: "/dashboard/user/affiliates/send-invitation",
        data: formData,
        contentType: false,
        processData: false,
        success: function ( data ) {
            toastr.success(magicai_localize.invitesent);
            document.getElementById( "send_invitation_button" ).disabled = false;
            document.getElementById( "send_invitation_button" ).innerHTML = magicai_localize.send;
        },
        error: function ( data ) {
            toastr.error(magicai_localize.errorsend)
            document.getElementById( "send_invitation_button" ).disabled = false;
            document.getElementById( "send_invitation_button" ).innerHTML = magicai_localize.send;
        }
    } );
    return false;
};


