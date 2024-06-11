export {};

declare global {
    interface CorporationDocument {
        id: number;
        doc_number: number;
        document_name: string;
        desc: string | null;
        expiration_date: Date;
        show_in_employee: boolean;
        attachment: string;
    }
    export type CorporationDocuments = CorporationDocument[];

    export interface EmployeeShift {
        employee_id: undefined;
        shift_id: undefined;
        day_date: null;

        started_at: undefined;
        ended_at: undefined;
    }

    // export interface Employee {
    //     id: number | string;
    //     name: string;
    //     total_multiplied_hours: string;
    //     shifts: {
    //         id: number | string;
    //         started_at: string;

    //         shift: {
    //             name: string;
    //             start_time: string;
    //             end_time: string;
    //         };
    //     };
    //     attendances: {
    //         id: number | string;
    //         start_time: string;
    //         end_time: string;
    //         day_date: string;
    //         status: "attended" | "absent";
    //     }[];
    // }
}
