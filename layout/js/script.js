window.addEventListener('load', () => {
    var rootElement = document.documentElement;
    var scrollToTopBtn = document.querySelector('.scrollToTopBtn');
    /* Adding element in posting recipe page */
    var i = 0;
    var strUnits = [];

    /* Scrolling handler */
    function handleScroll() {
        // Do something on scroll
        var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight;

        if ((rootElement.scrollTop / scrollTotal) > 0.10) {
            // Show button
            scrollToTopBtn.classList.add(('showBtn'));
        } else {
            // Hide button
            scrollToTopBtn.classList.remove('showBtn');
        }
    }

    handleScroll();

    function scrollToTop() {
        rootElement.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    scrollToTopBtn.addEventListener("click", scrollToTop);
    document.addEventListener("scroll", handleScroll);

    /* Removing all active class */
    function removeAllActiveClass() {
        $('.list-group-item.active').removeClass("active");
    }
    
    function init_strUnits() {
        $.ajax({
            url: 'index.php?module=recipe&action=get_encoded_units',
            dataType: 'json',
            type: 'post',
            success: function (data) {
                if (Array.isArray(data) && data) {
                    data.forEach(function(u) {
                        strUnits.push("<option>" + u.unit + "</option>")
                    });
                }
            }
        });
    }

    if ((url = new URLSearchParams($(location).attr('href'))) && url.has('action') && (url.get('action') == 'write_recipe' || url.get('action') == 'advanced_search_page'))
        init_strUnits();

    $('#add_ingredient_writing_recipe').click(() => {
        i++;
        $('#dynamic_ingredient').append("<tr id='row" + i + "'><td class='form-label-group'><label for='ingredient[" + i + "][name]'>Name <span class='text-danger'>*</span></label><input type='text' id='ingredient_" + i + "' class='form-control autocomplete' name='ingredient[" + i + "][name]' placeholder='Enter ingredient name' required></td><td class='form-label-group'><label for='ingredient[" + i + "][quantity]'>Quantity <span class='text-danger'>*</span></label><input type='number' name='ingredient[" + i + "][quantity]' min='0' placeholder='Enter ingredient quantity' class='form-control' required></td><td class='form-label-group'><label for='ingredient[" + i + "][unit]'>Unit <span class='text-danger'>*</span></label><select id='ingredient_" + i + "' name='ingredient[" + i + "][unit]' class='form-control autocomplete'><option> </option>" + strUnits +"</select></td><td><div class='d-flex justify-content-center'><button type='button' id='" + i + "' class='btn btn-danger btn_remove font-weight-bold mt-md-3'>X</button></div></td></tr>");
    });

    $('#add_ingredient_searching_recipe').click(() => {
        i++;
        $('#dynamic_ingredient').append("<tr id='row" + i + "'><td class='form-label-group text-left'><label for='ingredient[" + i + "]'>Name</label><input type='text' id='ingredient_" + i + "' class='form-control autocomplete' name='ingredient[" + i + "]' placeholder='Enter ingredient name'></td><td><div class='d-flex justify-content-center'><button type='button' id='" + i + "' class='btn btn-danger btn_remove font-weight-bold mt-md-3'>X</button></div></td></tr>");
    });

    $('#add_ustensile').click(() => {
        i++;
        $('#dynamic_ustensile').append("<tr id='row" + i + "'><td><input type='text' id='ustensile_" + i + "' class='form-control autocomplete' name='ustensile[" + i + "]' placeholder='Enter ustensile'></td><td><div class='d-flex justify-content-center'><button type='button' id='" + i + "' class='btn btn-danger btn_remove font-weight-bold'>X</button></div></td></tr>");
    });

    $('#add_theme').click(() => {
        i++;
        $('#dynamic_theme').append("<tr id='row" + i + "'><td><input type='text' id='theme_" + i + "' class='form-control autocomplete' name='theme[" + i + "]' placeholder='Enter theme'></td><td><div class='d-flex justify-content-center'><button type='button' id='" + i + "' class='btn btn-danger btn_remove font-weight-bold'>X</button></div></td></tr>");
    });

    $('#add_diet').click(() => {
        i++;
        $('#dynamic_diet').append("<tr id='row" + i + "'><td><input type='text' id='diet_" + i + "' class='form-control autocomplete' name='diet[" + i + "]' placeholder='Enter diet'></td><td><div class='d-flex justify-content-center'><button type='button' id='" + i + "' class='btn btn-danger btn_remove font-weight-bold'>X</button></div></td></tr>");
    });

    $('#add_dishtype').click(() => {
        i++;
        $('#dynamic_dishtype').append("<tr id='row" + i + "'><td><input type='text' id='dishtype_" + i + "' class='form-control autocomplete' name='dishtype[" + i + "]' placeholder='Enter dish type' required></td><td><div class='d-flex justify-content-center'><button type='button' id='" + i + "' class='btn btn-danger btn_remove font-weight-bold'>X</button></div></td></tr>");
    });

    /* Removing element in posting recipe page */
    $(document).on('click', '.btn_remove', function () {
        var button_id = $(this).attr('id');
        $('#row' + button_id + '').remove();
    });

    /* Submit button of posting recipe page */
    $('#submit').click(() => {
        $.ajax({
            url: $('#recipe_form').attr('action'),
            enctype: $('#recipe_form').attr('enctype'),
            method: $('#recipe_form').attr('method'),
            data: $('#recipe_form').serialize()
        });
    });

    $(document).on('keydown', '.autocomplete', function() {
        $('.autocomplete').autocomplete({
            source: function (request, response) {
                var id = $(this)[0].element[0].id.split('_')[0];
                console.log(id);
                $.ajax({
                    url: 'index.php?module=recipe&action=autocomplete',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        search: request.term,
                        table: id
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            select: function (event, ui) {
                $(this).val(ui.item.value);
                return false;
            }
        });
    });

    $('#notif-button').click(function() {
        console.log("pas content");
        if ($('#recipes-notif').css('display') == 'none')
             $('#recipes-notif').css('display', 'block');
        else
            $('#recipes-notif').css('display' ,'none');
        
    });
});