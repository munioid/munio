import React, { useState } from 'react'
import { router } from '@inertiajs/react'
import { Button, Badge } from '../../Components/Partial'

export default function Filter({ categories, tags, filters }) {
    const [search, setSearch] = useState(filters?.search || '')
    const [selectedCategory, setSelectedCategory] = useState(filters?.category || null)
    const [selectedTags, setSelectedTags] = useState(filters?.tag ? [filters.tag] : [])

    const updateFilters = (searchValue, categoryValue, tagsValue) => {
        const params = new URLSearchParams()

        if (categoryValue) params.append('category', categoryValue)
        if (tagsValue && tagsValue.length > 0) params.append('tag', tagsValue[0])
        if (searchValue) params.append('search', searchValue)

        router.visit(`/posts${params.toString() ? '?' + params.toString() : ''}`, {
            preserveScroll: true,
        })
    }

    const handleSearchSubmit = (e) => {
        e.preventDefault()
        updateFilters(search, selectedCategory, selectedTags)
    }

    const handleCategoryClick = (categoryId) => {
        const newCategory = selectedCategory === categoryId ? null : categoryId
        setSelectedCategory(newCategory)
        updateFilters(search, newCategory, selectedTags)
    }

    const handleTagClick = (tagId) => {
        const newTags = selectedTags.includes(tagId)
            ? selectedTags.filter(t => t !== tagId)
            : [tagId]
        setSelectedTags(newTags)
        updateFilters(search, selectedCategory, newTags)
    }

    const handleReset = () => {
        setSearch('')
        setSelectedCategory(null)
        setSelectedTags([])
        router.visit('/posts', { preserveScroll: true })
    }

    return (
        <div className="sticky top-0 z-10 bg-white border-b px-5 py-1 pb-4">
            {/* Search Form */}
            <form onSubmit={handleSearchSubmit} className="mt-4">
                <input
                    id="search"
                    type="search"
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    placeholder="Cari berita..."
                    className="w-full rounded-xl border border-gray-300 bg-white py-2 pl-4 pr-2 text-sm focus:outline-none focus:border-primary focus:ring-primary"
                />
            </form>

            {/* Categories */}
            <div className="mt-4 flex gap-2 overflow-x-auto pb-2">
                <Button
                    variant={selectedCategory === null ? 'primary' : 'ghost'}
                    size="sm"
                    onClick={() => handleCategoryClick(null)}
                    className="shrink-0 rounded-full"
                >
                    Semua
                </Button>

                {categories.map(category => (
                    <Button
                        key={category.id}
                        variant={selectedCategory === category.id ? 'primary' : 'ghost'}
                        size="sm"
                        onClick={() => handleCategoryClick(category.id)}
                        className="shrink-0 rounded-full"
                    >
                        {category.name}
                    </Button>
                ))}
            </div>

            {/* Tags */}
            <div className="mt-3 flex gap-2 overflow-x-auto pb-2">
                {tags.map(tag => (
                    <Button
                        key={tag.id}
                        variant={selectedTags.includes(tag.id) ? 'primary' : 'ghost'}
                        size="sm"
                        onClick={() => handleTagClick(tag.id)}
                        className="shrink-0 rounded-full"
                    >
                        #{tag.name}
                    </Button>
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
