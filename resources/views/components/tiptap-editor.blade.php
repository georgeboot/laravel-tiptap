<div
    x-data="tiptapEditor($wire.entangle('{{ $attributes->wire('model')->value() }}'){{ $attributes->wire('model')->hasModifier('defer') ? '.defer': '' }}, { enableImageUpload: @json($attributes->has('enable-image-upload')), maxSize: @json(config('laravel-tiptap.images.maxSize')) })"
    wire:ignore
    {{ $attributes->whereDoesntStartWith('wire:model') }}
    class="block w-full bg-white border border-gray-300 rounded-md shadow-sm focus:ring-blue-200 focus:border-blue-300 focus:ring focus:ring-opacity-75 sm:text-sm"
>
    <template x-if="editor">
        <div class="flex items-center justify-start p-4 space-x-1 border-b border-gray-300 menu">
            <button
                type="button"
                title="Heading"
                @click="Alpine.raw(editor).chain().toggleHeading({ level: 3 }).focus().run()"
                :class="isHeading ? 'bg-gray-300 text-gray-700' : 'text-gray-600'"
                class="flex items-center justify-center w-8 h-8 -ml-2 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                @svg('go-heading-16', 'h-4 w-4')
            </button>
            <div class="w-px h-4 bg-gray-300"></div>
            <button
                type="button"
                title="Bold"
                @click="Alpine.raw(editor).chain().toggleBold().focus().run()"
                :class="isBold ? 'bg-gray-300 text-gray-700' : 'text-gray-600'"
                class="flex items-center justify-center w-8 h-8 -ml-2 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                @svg('go-bold-16', 'h-4 w-4')
            </button>
            <button
                type="button"
                title="Italic"
                @click="Alpine.raw(editor).chain().toggleItalic().focus().run()"
                :class="isItalic ? 'bg-gray-300 text-gray-700' : 'text-gray-600'"
                class="flex items-center justify-center w-8 h-8 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                @svg('go-italic-16', 'h-4 w-4')
            </button>
            <button
                type="button"
                title="Strikethrough"
                @click="Alpine.raw(editor).chain().toggleStrike().focus().run()"
                :class="isStrike ? 'bg-gray-300 text-gray-700' : 'text-gray-600'"
                class="flex items-center justify-center w-8 h-8 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                @svg('go-strikethrough-16', 'h-4 w-4')
            </button>
            <div class="w-px h-4 bg-gray-300"></div>
            <button
                type="button"
                title="Ordered list"
                @click="Alpine.raw(editor).chain().toggleOrderedList().focus().run()"
                class="flex items-center justify-center w-8 h-8 text-gray-600 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                @svg('go-list-ordered-16', 'h-4 w-4')
            </button>
            <button
                type="button"
                title="Unordered list"
                @click="Alpine.raw(editor).chain().toggleBulletList().focus().run()"
                class="flex items-center justify-center w-8 h-8 text-gray-600 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                @svg('go-list-unordered-16', 'h-4 w-4')
            </button>
            <template x-if="options.enableImageUpload || options.enableLinks">
                <div class="w-px h-4 bg-gray-300"></div>
            </template>
            <template x-if="options.enableLinks">
                <button
                    type="button"
                    title="Add a link"
                    @click="captureLinkHref"
                    class="flex items-center justify-center w-8 h-8 text-gray-600 rounded hover:bg-gray-100 hover:text-gray-700"
                >
                    @svg('go-link-16', 'h-4 w-4')
                </button>
            </template>
            <template x-if="options.enableImageUpload">
                <button
                    type="button"
                    title="Upload an image"
                    @click="showFilePicker"
                    class="flex items-center justify-center w-8 h-8 text-gray-600 rounded hover:bg-gray-100 hover:text-gray-700"
                >
                    @svg('go-image-16', 'h-4 w-4')
                </button>
            </template>
        </div>
    </template>

    <template x-if="options.enableImageUpload">
        <input type="file" class="hidden" accept="image/*" multiple x-ref="picker" @change="handleFileSelect">
    </template>

    <div x-cloak x-ref="link-bubble-menu" class="inline-flex items-center text-white gap-x-3">
        <button type="button" class="hover:underline" @click="removeLink">unlink</button>
        <button type="button" class="hover:underline" @click="captureLinkHref">update</button>
        <a target="_blank" class="!text-white hover:!underline !no-underline" :href="currentLinkHref">visit</a>
    </div>

    <div x-cloak x-ref="image-bubble-menu" class="inline-flex items-center text-white gap-x-3">
        <button type="button" class="hover:underline" @click="removeImage">remove</button>
    </div>

    <div
        x-ref="editor"
        class="relative prose prose-sm px-4 overflow-y-auto max-h-[50vh] !w-full !max-w-none focus-within:outline-none"
        @drop="options.enableImageUpload && handleFileDrop($event)"
    ></div>
</div>
