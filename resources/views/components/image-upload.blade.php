@props(['name' => 'picture', 'value' => null])

<div class="mb-3">
    <label for="{{ $name }}">Bill Picture</label>
    <div id="{{ $name }}-drop-area" class="border rounded p-5 text-center" style="cursor: pointer;">
        <p>Drag & drop an image here, paste from clipboard, or click to select a file.</p>
        <input type="file" name="{{ $name }}" id="{{ $name }}" class="d-none">
        <img id="{{ $name }}-preview" src="{{ $value ? asset('storage/' . $value) : '' }}" alt="Image Preview" style="max-width: 200px; max-height: 200px; margin-top: 10px; {{ $value ? '' : 'display: none;' }}">
    </div>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
    (function() {
        const dropArea = document.getElementById('{{ $name }}-drop-area');
        const input = document.getElementById('{{ $name }}');
        const preview = document.getElementById('{{ $name }}-preview');

        dropArea.addEventListener('click', () => input.click());

        dropArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropArea.classList.add('border-primary');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('border-primary');
        });

        dropArea.addEventListener('drop', (event) => {
            event.preventDefault();
            dropArea.classList.remove('border-primary');
            const files = event.dataTransfer.files;
            if (files.length) {
                input.files = files;
                handleFiles(files);
            }
        });

        window.addEventListener('paste', (event) => {
            const items = (event.clipboardData || event.originalEvent.clipboardData).items;
            let file = null;
            for (const item of items) {
                if (item.type.indexOf('image') === 0) {
                    file = item.getAsFile();
                    break;
                }
            }
            if (file) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                handleFiles(input.files);
            }
        });

        input.addEventListener('change', () => {
            handleFiles(input.files);
        });

        function handleFiles(files) {
            if (files.length) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(files[0]);
            }
        }
    })();
</script>
@endpush
