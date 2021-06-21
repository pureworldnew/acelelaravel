@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <form class="tag-contact" action="{{ action("SubscriberController@updateSourceTags", [
                    'list_uid' => $subscriber->mailList->uid,
                    'uid' => $subscriber->uid,
                ]) }}"
                method="POST" class="form-validate-jqueryz"
            >
                {{ csrf_field() }}
                
                <h2 class="mb-3">{{ trans('messages.automation.profile.source_tag_contacts') }}</h3>
                <p>{!! trans('messages.automation.profile.source_tag_contact.intro', [
                    'name' => $subscriber->getFullName(),
                ]) !!}</p>
                    
                @include('helpers.form_control', [
                    'type' => 'select_tag',
                    'class' => '',
                    'label' => '',
                    'name' => 'source_tag[]',
                    'value' => $subscriber->getSourceTags(),
                    'help_class' => 'trigger',
                    'options' => $subscriber->getSourceTagOptions(),
                    'rules' => ['source_tag' => 'required'],
                    'multiple' => 'true',
                    'placeholder' => trans('messages.automation.contact.choose_source_tags'),
                ])

                <div class="mt-4">
                    <button class="btn btn-secondary">{{ trans('messages.automation.profile.tag') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('form.tag-contact').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var data = form.serialize();
            var url = form.attr('action');
            
            addMaskLoading('{{ trans('messages.automation.contacts.tagging_contacts') }}');

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                statusCode: {
                    // validate error
                    400: function (res) {
                        popup.loadHtml(res.responseText);

                        // remove masking
                        removeMaskLoading();
                    }
                },
                success: function (res) {
                    // hide tagContact
                    tagContact.hide();

                    // notify
                    notify('success', '{{ trans('messages.notify.success') }}', res.message);

                    // remove masking
                    removeMaskLoading();

                    location.reload();
                }
            });    
        });
    </script>
@endsection
