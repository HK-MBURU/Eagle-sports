import CategoryStoriesPage from "@/components/CategoryStoriesPage";

const RaceReports = () => {
  return (
    <CategoryStoriesPage
      defaultCategory="Race Reports"
      title="Race Reports"
      description="Comprehensive coverage and analysis of road races, marathons, and major athletic competitions."
      showAllCategories={false}
    />
  );
};

export default RaceReports;