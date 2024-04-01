//Components pour l'utilisation de SweetAlert2 bibliothèque

function AlertError(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        iconColor: '#dc3545',
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            title: 'alertTitle',
            text: 'alertText',
            confirmButton: 'alertErrorConfirmBtn'
        }
    })
}

function AlertWarning(title, text, onConfirmFunction) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        iconColor: '#ff9021',
        buttonsStyling: false,
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            title: 'alertTitle',
            text: 'alertText',
            confirmButton: 'alertWarningConfirmBtn',
            cancelButton: 'alertWarningCancelBtn'
        }
    }).then((res) => {
        if(res.isConfirmed) {
            onConfirmFunction();
        }
    })
}

function AlertInfo(title, text, onConfirmFunction) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'info',
        iconColor: '#3B71CA',
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            title: 'alertTitle',
            text: 'alertText',
            confirmButton: 'alertInfoConfirmBtn'
        }
    }).then((res) => {
        if(res.isConfirmed) {
            onConfirmFunction();
        }
    })
}

function AlertSuccess(title, text, onConfirmFunction) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'success',
        iconColor: '#14A44D',
        buttonsStyling: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            title: 'alertTitle',
            text: 'alertText',
            confirmButton: 'alertSuccessConfirmBtn'
        }
    }).then((res) => {
        if(res.isConfirmed) {
            onConfirmFunction();
        }
    })
}