/**
 * ALIDSC — Éditeur riche TipTap.
 *
 * Utilisation dans un formulaire Blade :
 *
 *   <textarea name="contenu" id="contenu-1"></textarea>
 *
 *   <script>
 *     document.addEventListener('DOMContentLoaded', () => {
 *       new AlidscEditor('#contenu-1');
 *     });
 *   </script>
 *
 * Le textarea est masqué et son contenu est synchronisé à chaque
 * modification de l'éditeur (via un hidden input qui porte le name).
 */
import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';

class AlidscEditor {
    constructor(selector, options = {}) {
        this.textarea = document.querySelector(selector);
        if (!this.textarea) return;

        this.placeholder = options.placeholder || 'Écrivez ici…';
        this.textarea.style.display = 'none';

        // Conteneur qui accueillera la barre d'outils + zone d'édition
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'alidsc-editor';

        this.toolbar = document.createElement('div');
        this.toolbar.className = 'alidsc-editor__toolbar';

        this.content = document.createElement('div');
        this.content.className = 'alidsc-editor__content';

        this.wrapper.appendChild(this.toolbar);
        this.wrapper.appendChild(this.content);
        this.textarea.parentNode.insertBefore(this.wrapper, this.textarea);

        this.editor = new Editor({
            element: this.content,
            extensions: [
                StarterKit.configure({
                    heading: { levels: [2, 3] },
                }),
                Link.configure({
                    openOnClick: false,
                    HTMLAttributes: { rel: 'noopener noreferrer', target: '_blank' },
                }),
                Placeholder.configure({ placeholder: this.placeholder }),
            ],
            content: this.textarea.value || '',
            onUpdate: ({ editor }) => {
                this.textarea.value = editor.getHTML();
            },
        });

        // Si l'éditeur est vide, on enregistre une chaîne vide
        this.textarea.value = this.editor.getHTML() === '<p></p>'
            ? ''
            : this.editor.getHTML();

        this.buildToolbar();

        // Sync avant soumission du formulaire
        const form = this.textarea.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                this.textarea.value = this.editor.getHTML();
            });
        }
    }

    buildToolbar() {
        const buttons = [
            { label: 'G',   title: 'Gras',        action: () => this.editor.chain().focus().toggleBold().run(),      isActive: () => this.editor.isActive('bold') },
            { label: 'I',   title: 'Italique',    action: () => this.editor.chain().focus().toggleItalic().run(),    isActive: () => this.editor.isActive('italic'), style: 'font-style:italic' },
            { label: 'S',   title: 'Barré',       action: () => this.editor.chain().focus().toggleStrike().run(),    isActive: () => this.editor.isActive('strike'), style: 'text-decoration:line-through' },
            { sep: true },
            { label: 'H2',  title: 'Titre 2',     action: () => this.editor.chain().focus().toggleHeading({ level: 2 }).run(), isActive: () => this.editor.isActive('heading', { level: 2 }) },
            { label: 'H3',  title: 'Titre 3',     action: () => this.editor.chain().focus().toggleHeading({ level: 3 }).run(), isActive: () => this.editor.isActive('heading', { level: 3 }) },
            { sep: true },
            { label: '•',   title: 'Liste',       action: () => this.editor.chain().focus().toggleBulletList().run(), isActive: () => this.editor.isActive('bulletList') },
            { label: '1.',  title: 'Liste num.',  action: () => this.editor.chain().focus().toggleOrderedList().run(), isActive: () => this.editor.isActive('orderedList') },
            { label: '❝',   title: 'Citation',    action: () => this.editor.chain().focus().toggleBlockquote().run(), isActive: () => this.editor.isActive('blockquote') },
            { sep: true },
            { label: '🔗',  title: 'Lien',        action: () => this.toggleLink() },
            { label: '—',   title: 'Séparateur',  action: () => this.editor.chain().focus().setHorizontalRule().run() },
        ];

        buttons.forEach((b) => {
            if (b.sep) {
                const sep = document.createElement('span');
                sep.className = 'alidsc-editor__sep';
                this.toolbar.appendChild(sep);
                return;
            }

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = b.label;
            btn.title = b.title;
            if (b.style) btn.setAttribute('style', b.style);

            btn.addEventListener('click', () => {
                b.action();
                this.refreshActiveStates();
            });

            this.toolbar.appendChild(btn);
            b._el = btn;
        });

        this.buttons = buttons.filter((b) => !b.sep);

        this.editor.on('selectionUpdate', () => this.refreshActiveStates());
        this.editor.on('transaction', () => this.refreshActiveStates());

        this.refreshActiveStates();
    }

    toggleLink() {
        const previousUrl = this.editor.getAttributes('link').href;
        const url = window.prompt('URL du lien :', previousUrl || 'https://');

        if (url === null) return;

        if (url === '') {
            this.editor.chain().focus().extendMarkRange('link').unsetLink().run();
            return;
        }

        this.editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
    }

    refreshActiveStates() {
        this.buttons.forEach((b) => {
            if (!b._el || !b.isActive) return;
            b._el.classList.toggle('is-active', b.isActive());
        });
    }
}

window.AlidscEditor = AlidscEditor;
