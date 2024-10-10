<div x-data="{
        show: false,
        observe() {
            let observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.show = true;
                        observer.unobserve(this.$el); // Stops observing after the first appearance
                    }
                });
            });
            observer.observe(this.$el);
        }
    }" x-init="observe()" x-bind:class="show ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-20'" {{
    $attributes->merge(['class' => 'transition-all ease-in-out transform opacity-0']) }}>
    {{ $slot }}
</div>