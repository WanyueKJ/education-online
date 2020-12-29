// event-lite.d.ts

declare class EventLite {
    static mixin(proto: any): any;

    on(event: string, listener: (...args: any[]) => void): this;

    once(event: string, listener: (...args: any[]) => void): this;

    off(event: string, listener: (...args: any[]) => void): this;

    emit(event: string, ...args: any[]): boolean;
}

export = EventLite;
