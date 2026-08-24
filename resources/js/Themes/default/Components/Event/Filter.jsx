import React, { useState, useRef } from 'react'
import { router } from '@inertiajs/react'
import { Button } from '../../Components/Partial'

export default function Filter({ categories, filters }) {
    const [search, setSearch] = useState(filters?.search || '')
    const [selectedCategory, setSelectedCategory] = useState(filters?.category || null)
    const debounceTimeoutRef = useRef(null)

    const handleFilterChange = (newSearch, newCategory) => {
        const params = new URLSearchParams()
        if (newSearch) params.append('search', newSearch)
        if (newCategory) params.append('category', newCategory)

        router.get(`/events?${params.toString()}`)
    }

    const handleSearchChange = (e) => {
        const value = e.target.value
        setSearch(value)

        // Clear previous timeout if it exists
        if (debounceTimeoutRef.current) {
            clearTimeout(debounceTimeoutRef.current)
        }

        // Set new debounced search
        debounceTimeoutRef.current = setTimeout(() => {
            handleFilterChange(value, selectedCategory)
        }, 300)
    }

    const handleCategoryClick = (categorySlug) => {
        setSelectedCategory(categorySlug)
        handleFilterChange(search, categorySlug)
    }

    return (
        <div className="sticky top-0 z-10 border-b bg-white px-5 py-4">
            {/* Search */}
            <div className="mt-4">
                <input
                    type="search"
                    value={search}
                    onChange={handleSearchChange}
                    placeholder="Cari acara..."
                    className="w-full rounded-xl border border-gray-300 bg-white py-2 pl-4 pr-2 text-sm focus:border-primary focus:outline-none"
                />
            </div>

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
                        variant={selectedCategory === category.slug ? 'primary' : 'ghost'}
                        size="sm"
                        onClick={() => handleCategoryClick(category.slug)}
                        className="shrink-0 rounded-full whitespace-nowrap"
                    >
                        {category.name}
                    </Button>
                ))}
            </div>
        </div>
    )
}
