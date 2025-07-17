import { apiSlice } from "./base-query";

export const listingApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    getAllListing: builder.query({
      query: () => ({
        url: "/listings",
        method: "GET",
      }),
    }),
    getDetailListing: builder.query({
      query: (slug: string) => ({
        url: `/listings/${slug}`,
        method: "GET",
      }),
    }),
  }),
});

export const { useGetAllListingQuery, useGetDetailListingQuery } = listingApi;
