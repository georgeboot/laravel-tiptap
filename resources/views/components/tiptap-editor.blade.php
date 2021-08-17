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
                <!-- icon: go-heading-16 -->
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M3.75 2a.75.75 0 01.75.75V7h7V2.75a.75.75 0 011.5 0v10.5a.75.75 0 01-1.5 0V8.5h-7v4.75a.75.75 0 01-1.5 0V2.75A.75.75 0 013.75 2z"/></svg>
            </button>
            <div class="w-px h-4 bg-gray-300"></div>
            <button
                type="button"
                title="Bold"
                @click="Alpine.raw(editor).chain().toggleBold().focus().run()"
                :class="isBold ? 'bg-gray-300 text-gray-700' : 'text-gray-600'"
                class="flex items-center justify-center w-8 h-8 -ml-2 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                <!-- icon: go-bold-16 -->
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 2a1 1 0 00-1 1v10a1 1 0 001 1h5.5a3.5 3.5 0 001.852-6.47A3.5 3.5 0 008.5 2H4zm4.5 5a1.5 1.5 0 100-3H5v3h3.5zM5 9v3h4.5a1.5 1.5 0 000-3H5z"/></svg>
            </button>
            <button
                type="button"
                title="Italic"
                @click="Alpine.raw(editor).chain().toggleItalic().focus().run()"
                :class="isItalic ? 'bg-gray-300 text-gray-700' : 'text-gray-600'"
                class="flex items-center justify-center w-8 h-8 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                <!-- icon: go-italic-16 -->
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6 2.75A.75.75 0 016.75 2h6.5a.75.75 0 010 1.5h-2.505l-3.858 9H9.25a.75.75 0 010 1.5h-6.5a.75.75 0 010-1.5h2.505l3.858-9H6.75A.75.75 0 016 2.75z"/></svg>
            </button>
            <button
                type="button"
                title="Strikethrough"
                @click="Alpine.raw(editor).chain().toggleStrike().focus().run()"
                :class="isStrike ? 'bg-gray-300 text-gray-700' : 'text-gray-600'"
                class="flex items-center justify-center w-8 h-8 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                <!-- icon: go-strikethrough-16 -->
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M7.581 3.25c-2.036 0-2.778 1.082-2.778 1.786 0 .055.002.107.006.157a.75.75 0 01-1.496.114 3.56 3.56 0 01-.01-.271c0-1.832 1.75-3.286 4.278-3.286 1.418 0 2.721.58 3.514 1.093a.75.75 0 11-.814 1.26c-.64-.414-1.662-.853-2.7-.853zm3.474 5.25h3.195a.75.75 0 000-1.5H1.75a.75.75 0 000 1.5h6.018c.835.187 1.503.464 1.951.81.439.34.647.725.647 1.197 0 .428-.159.895-.594 1.267-.444.38-1.254.726-2.676.726-1.373 0-2.38-.493-2.86-.956a.75.75 0 00-1.042 1.079C3.992 13.393 5.39 14 7.096 14c1.652 0 2.852-.403 3.65-1.085a3.134 3.134 0 001.12-2.408 2.85 2.85 0 00-.811-2.007z"/></svg>
            </button>
            <div class="w-px h-4 bg-gray-300"></div>
            <button
                type="button"
                title="Ordered list"
                @click="Alpine.raw(editor).chain().toggleOrderedList().focus().run()"
                class="flex items-center justify-center w-8 h-8 text-gray-600 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                <!-- icon: go-list-ordered-16 -->
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.003 2.5a.5.5 0 00-.723-.447l-1.003.5a.5.5 0 00.446.895l.28-.14V6H.5a.5.5 0 000 1h2.006a.5.5 0 100-1h-.503V2.5zM5 3.25a.75.75 0 01.75-.75h8.5a.75.75 0 010 1.5h-8.5A.75.75 0 015 3.25zm0 5a.75.75 0 01.75-.75h8.5a.75.75 0 010 1.5h-8.5A.75.75 0 015 8.25zm0 5a.75.75 0 01.75-.75h8.5a.75.75 0 010 1.5h-8.5a.75.75 0 01-.75-.75zM.924 10.32l.003-.004a.851.851 0 01.144-.153A.66.66 0 011.5 10c.195 0 .306.068.374.146a.57.57 0 01.128.376c0 .453-.269.682-.8 1.078l-.035.025C.692 11.98 0 12.495 0 13.5a.5.5 0 00.5.5h2.003a.5.5 0 000-1H1.146c.132-.197.351-.372.654-.597l.047-.035c.47-.35 1.156-.858 1.156-1.845 0-.365-.118-.744-.377-1.038-.268-.303-.658-.484-1.126-.484-.48 0-.84.202-1.068.392a1.858 1.858 0 00-.348.384l-.007.011-.002.004-.001.002-.001.001a.5.5 0 00.851.525zM.5 10.055l-.427-.26.427.26z"/></svg>
            </button>
            <button
                type="button"
                title="Unordered list"
                @click="Alpine.raw(editor).chain().toggleBulletList().focus().run()"
                class="flex items-center justify-center w-8 h-8 text-gray-600 rounded hover:bg-gray-100 hover:text-gray-700"
            >
                <!-- icon: go-list-unordered-16 -->
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2 4a1 1 0 100-2 1 1 0 000 2zm3.75-1.5a.75.75 0 000 1.5h8.5a.75.75 0 000-1.5h-8.5zm0 5a.75.75 0 000 1.5h8.5a.75.75 0 000-1.5h-8.5zm0 5a.75.75 0 000 1.5h8.5a.75.75 0 000-1.5h-8.5zM3 8a1 1 0 11-2 0 1 1 0 012 0zm-1 6a1 1 0 100-2 1 1 0 000 2z"/></svg>
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
                    <!-- icon: go-link-16 -->
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M7.775 3.275a.75.75 0 001.06 1.06l1.25-1.25a2 2 0 112.83 2.83l-2.5 2.5a2 2 0 01-2.83 0 .75.75 0 00-1.06 1.06 3.5 3.5 0 004.95 0l2.5-2.5a3.5 3.5 0 00-4.95-4.95l-1.25 1.25zm-4.69 9.64a2 2 0 010-2.83l2.5-2.5a2 2 0 012.83 0 .75.75 0 001.06-1.06 3.5 3.5 0 00-4.95 0l-2.5 2.5a3.5 3.5 0 004.95 4.95l1.25-1.25a.75.75 0 00-1.06-1.06l-1.25 1.25a2 2 0 01-2.83 0z"/></svg>
                </button>
            </template>
            <template x-if="options.enableImageUpload">
                <button
                    type="button"
                    title="Upload an image"
                    @click="showFilePicker"
                    class="flex items-center justify-center w-8 h-8 text-gray-600 rounded hover:bg-gray-100 hover:text-gray-700"
                >
                    <!-- icon: go-image-16 -->
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.75 2.5a.25.25 0 00-.25.25v10.5c0 .138.112.25.25.25h.94a.76.76 0 01.03-.03l6.077-6.078a1.75 1.75 0 012.412-.06L14.5 10.31V2.75a.25.25 0 00-.25-.25H1.75zm12.5 11H4.81l5.048-5.047a.25.25 0 01.344-.009l4.298 3.889v.917a.25.25 0 01-.25.25zm1.75-.25V2.75A1.75 1.75 0 0014.25 1H1.75A1.75 1.75 0 000 2.75v10.5C0 14.216.784 15 1.75 15h12.5A1.75 1.75 0 0016 13.25zM5.5 6a.5.5 0 11-1 0 .5.5 0 011 0zM7 6a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
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
