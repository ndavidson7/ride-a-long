@props(['enterDelay' => 600, 'leaveDelay' => 500, 'anchor', 'card'])

<div class="relative" x-data="{
    hovered: false,
    hoverCardTimeout: null,
    hoverCardLeaveTimeout: null,
    hoverCardEnter() {
        clearTimeout(this.hoverCardLeaveTimeout);
        if (this.hovered) return;
        clearTimeout(this.hoverCardTimeout);
        this.hoverCardTimeout = setTimeout(() => {
            this.hovered = true;
        }, {{ $enterDelay }});
    },
    hoverCardLeave() {
        clearTimeout(this.hoverCardTimeout);
        if (!this.hovered) return;
        clearTimeout(this.hoverCardLeaveTimeout);
        this.hoverCardLeaveTimeout = setTimeout(() => {
            this.hovered = false;
        }, {{ $leaveDelay }});
    }
}" @mouseenter="hoverCardEnter" @mouseleave="hoverCardLeave" @click.stop="">
    <a {{ $anchor->attributes }} x-ref="anchor">
        {{ $anchor }}
    </a>
    <div {{ $card->attributes->class(['z-[99]']) }} x-show="hovered" x-cloak x-transition
        x-anchor.offset.10="$refs.anchor">
        {{ $card }}
    </div>
</div>
