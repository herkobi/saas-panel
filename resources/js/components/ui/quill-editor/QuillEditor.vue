<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import 'quill/dist/quill.snow.css';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'İçerik yazın...',
    },
    height: {
        type: String,
        default: '200px'
    }
});

const emit = defineEmits(['update:modelValue']);
const editorElement = ref(null);
const quill = ref(null);

onMounted(() => {
    if (!window.Quill) {
        import('quill').then((module) => {
            window.Quill = module.default;
            initQuill();
        });
    } else {
        initQuill();
    }
});

const initQuill = () => {
    const toolbarOptions = [
        ['bold', 'italic', 'underline'], // kalın, italik, altı çizili
        [{ 'list': 'ordered' }, { 'list': 'bullet' }], // sıralı/numaralı listeler
        [{ 'header': [1, 2, 3, false] }], // başlık seviyeleri
        ['link'], // link ekleme
        [{ 'color': [] }, { 'background': [] }], // metin ve arka plan rengi
        ['clean'] // formatlama temizleme
    ];

    quill.value = new window.Quill(editorElement.value, {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: props.placeholder,
        theme: 'snow'
    });

    // İçerik değişikliğini dinle
    quill.value.on('text-change', () => {
        emit('update:modelValue', quill.value.root.innerHTML);
    });

    // İlk içeriği ayarla
    if (props.modelValue) {
        quill.value.root.innerHTML = props.modelValue;
    }
};

// Props'tan gelen modelValue değişikliklerini izleme
watch(() => props.modelValue, (newValue) => {
    if (quill.value && newValue !== quill.value.root.innerHTML) {
        quill.value.root.innerHTML = newValue;
    }
});
</script>

<template>
    <div class="quill-editor-container">
        <div ref="editorElement" :style="{ height: height }"></div>
    </div>
</template>

<style>
.quill-editor-container .ql-toolbar.ql-snow {
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

.quill-editor-container .ql-container.ql-snow {
    border-bottom-left-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
</style>
