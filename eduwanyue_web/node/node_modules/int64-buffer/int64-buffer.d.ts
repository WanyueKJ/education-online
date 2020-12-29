// TypeScript type definitions

type ArrayType = Uint8Array | ArrayBuffer | number[];

declare abstract class Int64
{
	constructor(value?: number);
	constructor(high: number, low: number);
	constructor(value: string, radix?: number);
	constructor(buf: Buffer);
	constructor(buf: Buffer, offset: number, value?: number);
	constructor(buf: Buffer, offset: number, high: number, low: number);
	constructor(buf: Buffer, offset: number, value: string, radix?: number);
	constructor(array: ArrayType);
	constructor(array: ArrayType, offset: number, value?: number);
	constructor(array: ArrayType, offset: number, high: number, low: number);
	constructor(array: ArrayType, offset: number, value: string, radix?: number);

	toNumber(): number;

	toJSON(): number;

	toString(radix?: number): string;

	toBuffer(raw?: boolean): Buffer;

	toArrayBuffer(raw?: boolean): ArrayBuffer;

	toArray(raw?: boolean): number[];
}

export declare class Int64BE extends Int64
{
	static isInt64BE(obj: any): obj is Int64BE;
}

export declare class Uint64BE extends Int64
{
	static isUint64BE(obj: any): obj is Uint64BE;
}

export declare class Int64LE extends Int64
{
	static isInt64LE(obj: any): obj is Int64LE;
}

export declare class Uint64LE extends Int64
{
	static isUint64LE(obj: any): obj is Uint64LE;
}
