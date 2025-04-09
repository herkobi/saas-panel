<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Link from '@tiptap/extension-link'
import Underline from '@tiptap/extension-underline'
import { ref, watch, onMounted } from 'vue'
import {
  Bold,
  Italic,
  Underline as UnderlineIcon,
  List,
  ListOrdered,
  Link2 as LinkIcon,
  Heading1,
  Heading2,
  Pilcrow
} from 'lucide-vue-next'
import { Button } from '@/components/ui/button'

const props = defineProps<{
  modelValue: string
  placeholder?: string
  editorClass?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

// Debounce fonksiyonu
function debounce(fn: Function, wait: number) {
  let timeout: ReturnType<typeof setTimeout> | null = null

  return function(...args: any[]) {
    if (timeout) {
      clearTimeout(timeout)
    }
    timeout = setTimeout(() => {
      fn(...args)
      timeout = null
    }, wait)
  }
}

// Değişikliği yayınlamak için debounce fonksiyonu
const emitChange = debounce((html: string) => {
  emit('update:modelValue', html)
}, 300) // 300ms debounce

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    Link.configure({
      openOnClick: false
    }),
    Underline,
  ],
  editorProps: {
    attributes: {
      class: 'prose prose-sm dark:prose-invert focus:outline-none w-full max-w-none p-2 min-h-[150px] text-sm',
    },
  },
  onUpdate: ({ editor }) => {
    // Sadece içeriği emit et, form gönderimi yapmaz
    const html = editor.getHTML()
    emitChange(html)
  },
})

// İçerik değişikliklerini izle
watch(() => props.modelValue, (newContent) => {
  // Editor içeriği ile props içeriği farklıysa güncelle
  const editorContent = editor.value?.getHTML()
  if (newContent !== editorContent) {
    editor.value?.commands.setContent(newContent, false)
  }
})

// Link ekleme işlevi
const isLinkActive = () => editor.value?.isActive('link') ?? false
const setLink = () => {
  const previousUrl = isLinkActive() ? editor.value?.getAttributes('link').href : ''
  const url = window.prompt('URL', previousUrl)

  // Eğer URL boşsa linki kaldır
  if (url === null) {
    return
  }

  // URL boşsa linki kaldır
  if (url === '') {
    editor.value?.chain().focus().extendMarkRange('link').unsetLink().run()
    return
  }

  // URL'yi http(s) ile başlatma kontrolü
  const validUrl = url.startsWith('http://') || url.startsWith('https://')
    ? url
    : `https://${url}`

  // Linki ayarla
  editor.value?.chain().focus().extendMarkRange('link').setLink({ href: validUrl }).run()
}

// Paragraf ve başlık işlevleri
const setParagraph = () => editor.value?.chain().focus().setParagraph().run()
const toggleHeading1 = () => editor.value?.chain().focus().toggleHeading({ level: 1 }).run()
const toggleHeading2 = () => editor.value?.chain().focus().toggleHeading({ level: 2 }).run()
</script>

<template>
  <div class="border rounded-md overflow-hidden">
    <!-- Araç çubuğu -->
    <div class="flex items-center gap-0.5 flex-wrap p-0.5 border-b bg-muted/50">
      <!-- Metin biçimlendirme -->
      <Button
        variant="ghost"
        size="sm"
        title="Kalın"
        class="h-6 w-6 p-0"
        @click="editor?.chain().focus().toggleBold().run()"
        :class="{ 'bg-muted': editor?.isActive('bold') }"
      >
        <Bold class="h-3 w-3" />
      </Button>

      <Button
        variant="ghost"
        size="sm"
        title="İtalik"
        class="h-6 w-6 p-0"
        @click="editor?.chain().focus().toggleItalic().run()"
        :class="{ 'bg-muted': editor?.isActive('italic') }"
      >
        <Italic class="h-3 w-3" />
      </Button>

      <Button
        variant="ghost"
        size="sm"
        title="Altı çizili"
        class="h-6 w-6 p-0"
        @click="editor?.chain().focus().toggleUnderline().run()"
        :class="{ 'bg-muted': editor?.isActive('underline') }"
      >
        <UnderlineIcon class="h-3 w-3" />
      </Button>

      <div class="w-px h-5 bg-border mx-0.5"></div>

      <!-- Başlıklar -->
      <Button
        variant="ghost"
        size="sm"
        title="Paragraf"
        class="h-6 w-6 p-0"
        @click="setParagraph"
        :class="{ 'bg-muted': editor?.isActive('paragraph') }"
      >
        <Pilcrow class="h-3 w-3" />
      </Button>

      <Button
        variant="ghost"
        size="sm"
        title="Başlık 1"
        class="h-6 w-6 p-0"
        @click="toggleHeading1"
        :class="{ 'bg-muted': editor?.isActive('heading', { level: 1 }) }"
      >
        <Heading1 class="h-3 w-3" />
      </Button>

      <Button
        variant="ghost"
        size="sm"
        title="Başlık 2"
        class="h-6 w-6 p-0"
        @click="toggleHeading2"
        :class="{ 'bg-muted': editor?.isActive('heading', { level: 2 }) }"
      >
        <Heading2 class="h-3 w-3" />
      </Button>

      <div class="w-px h-5 bg-border mx-0.5"></div>

      <!-- Listeler -->
      <Button
        variant="ghost"
        size="sm"
        title="Madde listesi"
        class="h-6 w-6 p-0"
        @click="editor?.chain().focus().toggleBulletList().run()"
        :class="{ 'bg-muted': editor?.isActive('bulletList') }"
      >
        <List class="h-3 w-3" />
      </Button>

      <Button
        variant="ghost"
        size="sm"
        title="Sıralı liste"
        class="h-6 w-6 p-0"
        @click="editor?.chain().focus().toggleOrderedList().run()"
        :class="{ 'bg-muted': editor?.isActive('orderedList') }"
      >
        <ListOrdered class="h-3 w-3" />
      </Button>

      <div class="w-px h-5 bg-border mx-0.5"></div>

      <!-- Link -->
      <Button
        variant="ghost"
        size="sm"
        title="Link"
        class="h-6 w-6 p-0"
        @click="setLink"
        :class="{ 'bg-muted': isLinkActive() }"
      >
        <LinkIcon class="h-3 w-3" />
      </Button>
    </div>

    <!-- Editör içeriği -->
    <EditorContent :editor="editor" class="overflow-y-auto" />
  </div>
</template>
