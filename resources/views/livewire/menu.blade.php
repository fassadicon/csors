<div x-data="{ categories: @js($foodCategories) }">
    <template x-for="category in categories"
        :key="category.id">
        <div>
            <p x-text="category.name"></p>
        </div>
    </template>
</div>
