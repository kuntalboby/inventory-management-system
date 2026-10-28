function updateCkeditor(counter) {
    if (typeof CKEDITOR !== 'undefined') {
        let id = `content_${counter}`;
        if (CKEDITOR.instances[id]) {
            CKEDITOR.instances[id].destroy(true);
        }
        CKEDITOR.replace(id, {
            allowedContent: true,
            extraAllowedContent: 'i(*)',
            protectedSource: [/<i[^>]*><\/i>/g],
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            fillEmptyBlocks: false,
            ignoreEmptyParagraph: true
        });
    }
}

function sectioncntl(mode, id) {
    let sectionCounter = parseInt($('#additionalSections').attr('data-val'));
    let counter = sectionCounter + 1;

    $('#additionalSections').attr('data-val', counter);

    if (mode === 'add') {
        let html = `
        <div class="row innerSections pageSections" id="section_${counter}">
            <div class="card-header mb-3 w-80">
                <h3 class="card-title draggableSection">
                    <span class="handle ui-sortable-handle">
                        <i class="fas fa-ellipsis-v"></i>
                        <i class="fas fa-ellipsis-v"></i>
                    </span>
                    Section-<span class="secName">${counter}</span>
                </h3>
            </div>
            <div class="card-tools">
                <a onclick="trashSection(${counter});" class="btn btn-danger">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
            <input type="hidden" class="form-control" name="data[${counter}][sort_position]" id="sort_position_section_${counter}" value="${counter}"/>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="heading[${counter}]">Heading </label>
                    <input type="text" class="form-control" name="data[${counter}][heading]" id="heading_${counter}"/>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="content[${counter}]">Content *</label>
                    <textarea id="content_${counter}" name="data[${counter}][content]" class="form-control ckeditor" placeholder="Enter full content"></textarea>
                </div>
            </div>
        </div>`;
        
        $('#additionalSections').append(html);
        updateCkeditor(counter);
        $('.additionalSections .card-header').css('cursor', 'move');
        updateSortPositions();
    }
}

// Enable sortable with CKEditor safe handling
$(function () {
    $('#additionalSections').sortable({
        items: '.innerSections',
        handle: '.card-header',
        start: function () {
            // Save data & destroy CKEditors before sorting
            $('#additionalSections textarea').each(function () {
                let id = $(this).attr('id');
                if (CKEDITOR.instances[id]) {
                    $(this).val(CKEDITOR.instances[id].getData());
                    CKEDITOR.instances[id].destroy(true);
                }
            });
        },
        stop: function () {
            // Re-init CKEditors after sorting
            $('#additionalSections textarea').each(function () {
                let id = $(this).attr('id');
                let counter = id.split('_')[1]; // Get the number after 'content_'
                updateCkeditor(counter);
            });
            updateSortPositions();
        }
    });
});

function updateSortPositions() {
    $('#additionalSections .pageSections').each(function (index) {
        let newPos = index + 1;
        $(this).find('input[name$="[sort_position]"]').val(newPos);
        $(this).find('.secName').text(newPos);
    });
}

function trashSection(_counter) {
    if (confirm('Are you sure you want to delete this?')) {

        // Destroy CKEditor before removing section
        let id = `content_${_counter}`;
        if (CKEDITOR.instances[id]) {
            CKEDITOR.instances[id].destroy(true);
        }
        $('#section_' + _counter).fadeOut('slow');
        setTimeout(() => {
            $('#section_' + _counter).remove();
            updateSortPositions();
        }, 400);
        
    }
}