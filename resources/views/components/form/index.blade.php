<form method="{{ $form['method'] ?? 'post' }}">
    @csrf
    @foreach($form['fields'] as $field)
    @switch($field['type'])
    @case('text')
    @case('email')
    @case('password')
    <x-form.input :props="$field" />
    @break
    @case('select')
    <x-form.select :props="$field" />
    @break
    @endswitch
    @endforeach
    @if(empty($form['buttons']))
    <button type="submit" class="btn btn-primary">{{ !empty($form['button_text']) ? $form['button_text'] : __('app.submit') }}</button>
    @endif
</form>