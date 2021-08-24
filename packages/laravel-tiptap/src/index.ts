import Alpine from 'alpinejs'
import {Editor} from '@tiptap/core'
import ExtensionBold from '@tiptap/extension-bold'
import ExtensionBubbleMenu from '@tiptap/extension-bubble-menu'
import ExtensionHeading from '@tiptap/extension-heading'
import ExtensionImage from '@tiptap/extension-image'
import ExtensionItalic from '@tiptap/extension-italic'
import ExtensionLink from '@tiptap/extension-link'
import ExtensionOrderedList from '@tiptap/extension-ordered-list'
import ExtensionBulletList from '@tiptap/extension-bullet-list'
import ExtensionListItem from '@tiptap/extension-list-item'
import ExtensionParagraph from '@tiptap/extension-paragraph'
import ExtensionStrike from '@tiptap/extension-strike'
import ExtensionText from '@tiptap/extension-text'
import ExtensionDocument from '@tiptap/extension-document'
import 'tippy.js/dist/tippy.css'
import ImageBlobReduce from 'image-blob-reduce'
import { TextSelection } from 'prosemirror-state'
import Cookies from 'js-cookie'

interface GetsS3UrlResponse {
    uploadUrl: string
    uploadUrlFormData: { [key: string]: string }
    uploadKeyPrefix: string
    downloadUrlPrefix: string
}

const data = (content: any, userOptions: any) => ({
    editor: null,
    content,
    isBold: false,
    isItalic: false,
    isStrike: false,
    isHeading: false,
    isLink: false,
    isImage: false,
    options: {
        enableImageUpload: false,
        enableLinks: true,
        maxSize: 1000,
        generateImageUploadConfigUrl: '/laravel-tiptap/generate-image-upload-config',
        ...userOptions,
    },
    imageUploadConfig: null as null | GetsS3UrlResponse,
    init() {
        // @ts-ignore
        this.editor = new Editor({
            // @ts-ignore
            element: this.$refs['editor'],
            extensions: [
                ExtensionBold,
                ExtensionBubbleMenu.configure({
                    pluginKey: 'link-bubble-menu',
                    // @ts-ignore
                    element: this.$refs['link-bubble-menu'],
                    shouldShow: ({ editor }) => editor.isActive('link'),
                }),
                ExtensionBubbleMenu.configure({
                    pluginKey: 'image-bubble-menu',
                    // @ts-ignore
                    element: this.$refs['image-bubble-menu'],
                    shouldShow: ({ editor }) => editor.isActive('image'),
                }),
                ExtensionHeading,
                ExtensionImage,
                ExtensionItalic,
                ExtensionLink.configure({
                    HTMLAttributes: { target: '_blank', rel: 'noopener' },
                    openOnClick: false,
                }),
                ExtensionOrderedList,
                ExtensionBulletList,
                ExtensionListItem,
                ExtensionParagraph,
                ExtensionStrike,
                ExtensionText,
                ExtensionDocument,
            ],
            content: this.content,
            onUpdate: ({ editor }) => {
                this.content = editor.getHTML()
            },
            onSelectionUpdate: () => {
                // @ts-ignore
                this.isBold = this.editor.isActive('bold')
                // @ts-ignore
                this.isItalic = this.editor.isActive('italic')
                // @ts-ignore
                this.isStrike = this.editor.isActive('strike')
                // @ts-ignore
                this.isHeading = this.editor.isActive('heading')
                // @ts-ignore
                this.isLink = this.editor.isActive('link')
                // @ts-ignore
                this.isImage = this.editor.isActive('image')
            },
        })

        // @ts-ignore
        this.$watch('content', (content) => {
            // If the new content matches TipTap's then we just skip.
            if (content === Alpine.raw(this.editor).getHTML()) return
            /*
            Otherwise, it means that a force external to TipTap
            is modifying the data on this Alpine component,
            which could be Livewire itself.
            In this case, we just need to update TipTap's
            content and we're good to do.
            For more information on the `setContent()` method, see:
                https://www.tiptap.dev/api/commands/set-content
            */
            Alpine.raw(this.editor).commands.setContent(content, false)
        })
    },

    get currentLinkHref() {
        if (! this.editor) return ''

        const state = Alpine.raw(this.editor).state
        const { from, to } = state.selection

        let marks: any = []
        state.doc.nodesBetween(from, to, (node: any) => {
            marks = [...marks, ...node.marks]
        })

        const mark = marks.find((markItem: any) => markItem.type.name === 'link')

        return mark && mark.attrs.href ? mark.attrs.href : ''
    },

    captureLinkHref() {
        const href = window.prompt('Please give the URL', this.currentLinkHref)

        if (! href) return

        Alpine.raw(this.editor).chain().extendMarkRange('link').setLink({ href }).focus().run()
    },

    removeLink() {
        Alpine.raw(this.editor).chain().extendMarkRange('link').unsetLink().focus().run()
    },

    showFilePicker() {
        // @ts-ignore
        this.$refs['picker'].click()
    },

    async handleFileSelect(event: Event) {
        const target = event.target as HTMLInputElement

        if (!target.files?.length) return

        for await (const file of Array.from(target.files)) {
            await this.handleUpload(file)
        }

        // reset picker
        (<HTMLInputElement>event.target).value = ''
    },

    async handleFileDrop(event: DragEvent) {
        event.stopPropagation()
        event.preventDefault()

        if (!event.dataTransfer) return

        const editor = Alpine.raw(this.editor) as Editor
        const coordinates = editor.view.posAtCoords({ left: event.clientX, top: event.clientY })

        for await (const file of Array.from(event.dataTransfer.files)) {
            if (! file.type.startsWith('image/')) {
                return
            }

            await this.handleUpload(file, coordinates?.pos)
        }
    },

    async getImageUploadConfig(): Promise<GetsS3UrlResponse> {
        if (this.imageUploadConfig) return this.imageUploadConfig

        const getsS3UrlResponse = await typedFetch<GetsS3UrlResponse>(this.options.generateImageUploadConfigUrl, {
            method: 'post',
            headers: [
                ['X-XSRF-TOKEN', Cookies.get('XSRF-TOKEN') ?? ''],
            ],
        })

        if (!getsS3UrlResponse.data) {
            throw 'Something went wrong'
        }

        this.imageUploadConfig = getsS3UrlResponse.data

        return this.imageUploadConfig
    },

    async handleUpload(file: File, position?: number) {
        const imageUploadConfig = await this.getImageUploadConfig()

        const formData = new FormData()
        for (const [key, value] of Object.entries(imageUploadConfig.uploadUrlFormData)) {
            formData.set(key, value)
        }

        // resize our image
        const resizer = ImageBlobReduce()
        const resizedFile = await resizer.toBlob(file, {
            max: this.options.maxSize,
        })

        formData.set('Content-Type', file.type)
        formData.set('key', `${imageUploadConfig.uploadKeyPrefix}/${file.name}`)
        formData.append('file', resizedFile)

        const uploadResponse = await typedFetch(imageUploadConfig.uploadUrl, {
            method: 'post',
            body: formData,
        })

        if (uploadResponse.status !== 201) {
            throw 'something went wrong while uploading the image'
        }

        const imageUrl = `${imageUploadConfig.downloadUrlPrefix}${file.name}`

        const editor = Alpine.raw(this.editor) as Editor

        const node = editor.schema.nodes.image.create({
            src: imageUrl,
        })

        const insertTransaction = editor.view.state.tr.insert(
            position ?? editor.view.state.selection.anchor,
            node
        )

        editor.view.dispatch(insertTransaction)

        const endPos = editor.state.selection.$to.after() - 1
        const resolvedPos = editor.state.doc.resolve(endPos)
        const moveCursorTransaction = editor.view.state.tr.setSelection(new TextSelection(resolvedPos))

        editor.view.dispatch(moveCursorTransaction.scrollIntoView())
    },

    removeImage() {
        const state = Alpine.raw(this.editor).state
        const view = Alpine.raw(this.editor).view
        const transaction = state.tr
        const pos = state.selection.$anchor.pos

        const nodeSize = state.selection.node.nodeSize

        transaction.delete(pos, pos + nodeSize)

        view.dispatch(transaction)
    },
})

type FetchResponse<T> = Response & {
    data?: T
}

export async function typedFetch<T>(
    request: RequestInfo,
    init?: RequestInit | undefined,
): Promise<FetchResponse<T>> {
    const response = await fetch(request, init)

    const contentType = response.headers.get('content-type')
    if (contentType && contentType === 'application/json') {
        const data = await response.json() as T

        return {
            ...response,
            data,
        }
    }

    return response
}

export default data
