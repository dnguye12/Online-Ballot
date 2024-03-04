function AlertError(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        buttonsStyling: false,
        customClass: {
            title: 'alertTitle',
            text: 'alertText',
            confirmButton: 'alertErrorConfirmBtn'
        }
    })
}

function AlertWarning(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            title: 'alertTitle',
            text: 'alertText',
            confirmButton: 'alertConfirmBtn',
            cancelButton: 'alertCancelBtn'
        }
    })
}