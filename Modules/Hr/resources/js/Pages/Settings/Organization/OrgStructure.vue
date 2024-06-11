<script setup lang="ts">
const breadcrumbs: BreadCrumbs = [
    {
        title: "Settings",
        href: "Settings",
    },
];
</script>

<template>
    <Header title="test title" :breadcrumbs>
        <template #actions>
            <Button label="add new" variant="success" />
        </template>
    </Header>
    <div class="page-content">
        <div class="panel">
            <Table>
                <TableCaption>A list of your recent invoices.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> Invoice </TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Method</TableHead>
                        <TableHead class="text-right"> Amount </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow>
                        <TableCell class="font-medium"> INV001 </TableCell>
                        <TableCell>Paid</TableCell>
                        <TableCell>Credit Card</TableCell>
                        <TableCell class="text-right"> .00 </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>

<style></style>
