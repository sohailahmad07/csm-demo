window.ask = async function (name) {
    let model = document.querySelector(`[data-modal='${name}']`)

    let confirm = model.querySelector('#confirm')
    if (!confirm) {
        console.error(`Modal ${name} is missing confirm button ids. Please add them to the modal id="confirm" confirmation button`)
        return
    }

    return await new Promise((resolve) => {
        let controller = new AbortController();
        let signal = controller.signal;

        const onCancel = () => {
            resolve(false);
            controller.abort();
        }
        const onConfirm = () => {
            resolve(true);
            controller.abort();
        }

        model.addEventListener('close', onCancel, {signal})
        model.addEventListener('cancel', onCancel, {signal})
        confirm.addEventListener('click', onConfirm, {signal})

    })
}
