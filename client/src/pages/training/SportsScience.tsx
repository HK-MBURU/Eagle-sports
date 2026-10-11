import CategoryStoriesPage from "@/components/CategoryStoriesPage";

const SportsScience = () => {
  return (
    <CategoryStoriesPage
      defaultCategory="Sports Science"
      title="Sports Science & Research"
      description="Latest research, analysis, and scientific insights in athletics and sports performance."
      showAllCategories={false}
    />
  );
};

export default SportsScience;