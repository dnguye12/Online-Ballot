function AlertError(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'error',
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