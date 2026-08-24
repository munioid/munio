import React, { useState } from 'react'
import { router } from '@inertiajs/react'

export default function Filter({ categories, filters }) {
    const [search, setSearch] = useState(filters?.search || '')
    const [selectedCategory, setSelectedCategory] = useState(filters?.category || '')

    const handleFilterChange = (newSearch = search, newCategory = selectedCategory) => {
        const params = new URLSearchParams()
        if (newSearch) params.append('search', newSearch)
        if (newCategory) params.append('category', newCategory)

        router.get(`/events?${params.toString()}`)
    }

    const handleSearchChange = (e) => {
        const value = e.target.value
        setSearch(value)
        // Debounce the search
        setTimeout(() => {
            handleFilterChange(value, selectedCategory)
        }, 300)
    }

    const handleCategoryChange = (e) => {
        const value = e.target.value
        setSelectedCategory(value)
        handleFilterChange(search, value)
    }

    const handleClearFilters = () => {
        setSearch('')
        setSelectedCategory('')
        router.get('/events')
    }

    const hasFilters = search || selectedCategory

    return (
        <div className="bg-white px-5 py-4 shadow-sm">
            <div className="space-y-4">
                {/* Search */}
                <div>
                    <label className="mb-2 block text-sm font-medium">
                        Cari Event
                    </label>
                    <input
                        type="text"
                        value={search}
                        onChange={handleSearchChange}
                        placeholder="Cari event..."
                        className="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:outline-none"
                    />
                </div>

                {/* Category Filter */}
                <div>
                    <label className="mb-2 block text-sm font-medium">
                        Kategori
                    </label>
                    <select
                        value={selectedCategory}
                        onChange={handleCategoryChange}
                        className="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:outline-none"
                    >
                        <option value="">Semua Kategori</option>
                        {categories.map(category => (
                            <option key={category.id} value={category.id}>
                                {category.name}
                            </option>
                        ))}
                    </select>
                </div>

                {/* Clear Filters */}
                {hasFilters && (
                    <button
                        onClick={handleClearFilters}
                        className="w-full rounded-xl border border-gray-300 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
                    >
                        Hapus Filter
                    </button>
                )}
            </div>
        </div>
    )
}
