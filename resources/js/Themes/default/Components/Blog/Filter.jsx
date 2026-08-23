import React, { useState, useEffect } from 'react'
import { router } from '@inertiajs/react'

export default function Filter({ categories, tags, filters }) {
    const [search, setSearch] = useState(filters?.search || '')
    const [selectedCategory, setSelectedCategory] = useState(filters?.category || null)
    const [selectedTags, setSelectedTags] = useState(filters?.tag ? [filters.tag] : [])
    const [searchTimeout, setSearchTimeout] = useState(null)

    // Handle search with debounce
    useEffect(() => {
        clearTimeout(searchTimeout)
        const timeout = setTimeout(() => {
            updateFilters({ search, selectedCategory, selectedTags })
        }, 300)
        setSearchTimeout(timeout)
    }, [search])

    const updateFilters = (filterState = {}) => {
        const params = new URLSearchParams()

        const category = filterState.selectedCategory !== undefined ? filterState.selectedCategory : selectedCategory
        const tagsToUse = filterState.selectedTags !== undefined ? filterState.selectedTags : selectedTags
        const searchToUse = filterState.search !== undefined ? filterState.search : search

        if (category) params.append('category', category)
        if (tagsToUse.length > 0) params.append('tag', tagsToUse[0])
        if (searchToUse) params.append('search', searchToUse)

        router.visit(`/posts${params.toString() ? '?' + params.toString() : ''}`, {
            preserveScroll: true,
        })
    }

    const handleCategoryClick = (categoryId) => {
        const newCategory = selectedCategory === categoryId ? null : categoryId
        setSelectedCategory(newCategory)
        updateFilters({ selectedCategory: newCategory, selectedTags, search })
    }

    const handleTagClick = (tagId) => {
        const newTags = selectedTags.includes(tagId)
            ? selectedTags.filter(t => t !== tagId)
            : [tagId]
        setSelectedTags(newTags)
        updateFilters({ selectedCategory, selectedTags: newTags, search })
    }

    const handleReset = () => {
        setSearch('')
        setSelectedCategory(null)
        setSelectedTags([])
        router.visit('/posts', { preserveScroll: true })
    }

    return (
        <div className="sticky top-0 z-10 bg-white border-b px-5 py-1 pb-4">
            {/* Search */}
            <div className="mt-4">
                <input
                    id="search"
                    type="search"
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    placeholder="Cari berita..."
                    className="w-full rounded-xl border border-gray-300 bg-white py-2 pl-4 pr-2 text-sm focus:outline-none focus:border-primary focus:ring-primary"
                />
            </div>

            {/* Categories */}
            <div className="mt-4 flex gap-2 overflow-x-auto pb-2">
                <button
                    onClick={() => handleCategoryClick(null)}
                    className={`shrink-0 rounded-full px-4 py-2 text-sm transition ${
                        selectedCategory === null
                            ? 'bg-primary text-white'
                            : 'border border-gray-200'
                    }`}
                >
                    Semua
                </button>

                {categories.map(category => (
                    <button
                        key={category.id}
                        onClick={() => handleCategoryClick(category.id)}
                        className={`shrink-0 rounded-full px-4 py-2 text-sm transition ${
                            selectedCategory === category.id
                                ? 'bg-primary text-white'
                                : 'border border-gray-200'
                        }`}
                    >
                        {category.name}
                    </button>
                ))}
            </div>

            {/* Tags */}
            <div className="mt-3 flex gap-2 overflow-x-auto pb-2">
                {tags.map(tag => (
                    <button
                        key={tag.id}
                        onClick={() => handleTagClick(tag.id)}
                        className={`shrink-0 rounded-full px-4 py-2 text-sm transition ${
                            selectedTags.includes(tag.id)
                                ? 'bg-primary text-white'
                                : 'border border-gray-200'
                        }`}
                    >
                        #{tag.name}
                    </button>
                ))}
            </div>

            {/* Reset Button */}
            {(search || selectedCategory || selectedTags.length > 0) && (
                <div className="mt-3 px-0">
                    <button
                        onClick={handleReset}
                        className="text-sm text-primary font-medium hover:underline"
                    >
                        Reset Filter
                    </button>
                </div>
            )}
        </div>
    )
}
