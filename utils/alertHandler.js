function AlertError(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        customClass: {
            title: 'alertTitle',
            text: 'alertText',
            confirmButton: 'alertConfirmBtn'
        }
    })
}