import './bootstrap'
import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'
import { route } from 'ziggy-js'

// Make route available globally
window.route = route

createInertiaApp({
    resolve: (name) => {
        // Try to load from the theme-specific directory first
        const pages = import.meta.glob('./Themes/*/Pages/**/*.jsx', { eager: true })

        // Check for theme-specific page
        const themeName = window.document.documentElement.dataset.theme || 'default'
        const themePageKey = `./Themes/${themeName}/Pages/${name}.jsx`

        if (themePageKey in pages) {
            return pages[themePageKey]
        }

        // Fallback to default theme
        const defaultPageKey = `./Themes/default/Pages/${name}.jsx`
        if (defaultPageKey in pages) {
            return pages[defaultPageKey]
        }

        throw new Error(`Page not found: ${name}`)
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />)
    },
})
