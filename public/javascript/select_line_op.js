$(document).on('change', '#product_liness, #product_ops', function () {
    let $field = $(this)
    let $linessField = $('#product_liness')
    let $form = $field.closest('form')
    let target = '#' + $field.attr('id').replace('liness', 'ops')
    // Les données à envoyer en Ajax
    let data = {}
    data[$linessField.attr('name')] = $linessField.val()
    data[$field.attr('name')] = $field.val()
    // On soumet les données
    $.post($form.attr('action'), data).then(function (data) { // On récupère le nouveau <select>
        let $input = $(data).find(target)
        // On remplace notre <select> actuel
        $(target).replaceWith($input)
    })
})