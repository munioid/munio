import React, { useState } from 'react'
import { useForm } from '@inertiajs/react'

export default function Filter({ categories, tags, filters, onFilterChange, onSearch }) {
    const [isExpanded, setIsExpanded] = useState(false)
    const { data, setData, get } = useForm({
        category: filters?.category || '',
        tag: filters?.tag || '',
        search: filters?.search || '',
    })

    const handleSearchSubmit = (e) => {
        e.preventDefault()
        const params = new URLSearchParams()
        if (data.category) params.append('category', data.category)
        if (data.tag) params.append('tag', data.tag)
        if (data.search) params.append('search', data.search)

        window.location.href = `/posts${params.toString() ? '?' + params.toString() : ''}`
    }

    const handleReset = () => {
        setData({ category: '', tag: '', search: '' })
        window.location.href = '/posts'
    }

    const isFiltersActive = data.category || data.tag || data.search

    return (
        <div className="bg-white rounded-lg shadow-md p-6">
            {/* Mobile Toggle */}
            <div className="md:hidden mb-4">
                <button
                    onClick={() => setIsExpanded(!isExpanded)}
                    className="w-full flex items-center justify-between text-lg font-semibold text-gray-900"
                >
                    <span>Filter & Cari</span>
                    <svg
                        className={`w-5 h-5 transition transform ${isExpanded ? 'rotate-180' : ''}`}
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </button>
            </div>

            {/* Filter Content */}
            <form onSubmit={handleSearchSubmit} className={`space-y-6 ${!isExpanded && 'hidden md:block'}`}>
                {/* Search */}
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                        Cari Artikel
                    </label>
                    <input
                        type="text"
                        placeholder="Ketik judul atau kata kunci..."
                        value={data.search}
                        onChange={(e) => setData('search', e.target.value)}
                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent outline-none transition"
                    />
                </div>

                {/* Categories Filter */}
                {categories.length > 0 && (
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Kategori
                        </label>
                        <select
                            value={data.category}
                            onChange={(e) => setData('category', e.target.value)}
                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent outline-none transition"
                        >
                            <option value="">Semua Kategori</option>
                            {categories.map(category => (
                                <option key={category.id} value={category.id}>
                                    {category.name}
                                </option>
                            ))}
                        </select>
                    </div>
                )}

                {/* Tags Filter */}
                {tags.length > 0 && (
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-3">
                            Tag
                        </label>
                        <div className="flex flex-wrap gap-2">
                            {tags.map(tag => (
                                <label
                                    key={tag.id}
                                    className={`flex items-center px-3 py-1 rounded-full cursor-pointer transition ${
                                        data.tag === tag.id.toString()
                                            ? 'bg-[var(--primary-color)] text-white'
                                            : 'bg-gray-200 text-gray-800 hover:bg-gray-300'
                                    }`}
                                >
                                    <input
                                        type="radio"
                                        name="tag"
                                        value={tag.id}
                                        checked={data.tag === tag.id.toString()}
                                        onChange={(e) => setData('tag', e.target.value)}
                                        className="mr-2"
                                    />
                                    <span className="text-sm">{tag.name}</span>
                                </label>
                            ))}
                            {data.tag && (
                                <button
                                    type="button"
                                    onClick={() => setData('tag', '')}
                                    className="px-3 py-1 text-sm text-gray-600 hover:text-gray-900"
                                >
                                    ✕ Clear
                                </button>
                            )}
                        </div>
                    </div>
                )}

                {/* Action Buttons */}
                <div className="flex gap-2 pt-4 border-t border-gray-200">
                    <button
                        type="submit"
                        className="flex-1 py-2 px-4 bg-[var(--primary-color)] text-white font-medium rounded-lg hover:opacity-90 transition"
                    >
                        Cari
                    </button>
                    {isFiltersActive && (
                        <button
                            type="button"
                            onClick={handleReset}
                            className="flex-1 py-2 px-4 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition"
                        >
                            Reset
                        </button>
                    )}
                </div>
            </form>
        </div>
    )
}
